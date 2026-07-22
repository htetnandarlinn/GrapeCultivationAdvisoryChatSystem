<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\Entities\User;
use App\Application\NotificationManagement\NotificationService;
use App\Infrastructure\Persistence\Repositories\PermissionRepository;
use App\Infrastructure\Persistence\Repositories\RoleRepository;
use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Views\View;
use App\Shared\Exceptions\ValidationException;
use App\Infrastructure\Security\ReCaptchaValidator;

final class AuthController
{
    public function __construct(
        private RegisterUserHandler $registerHandler,
        private LoginUserHandler $loginHandler,
        private RegisterRequestValidator $registerValidator,
        private LoginRequestValidator $loginValidator,
        private UserRepositoryInterface $userRepository,
        private ActivityRepositoryInterface $activityRepository,
        private RoleRepository $roleRepo,
        private PermissionRepository $permRepo,
        private ?NotificationService $notificationService = null,
        private ?ReCaptchaValidator $reCaptchaValidator = null,
    ) {}

    public function showRegister()
    {
        return View::render('auth/register', [], '_standalone_');
    }

    public function showLogin()
    {
        return View::render('auth/login', [], '_standalone_');
    }

    public function register(): void
    {
        $payload = $_POST;

        try {
            if ($this->reCaptchaValidator && RECAPTCHA_SECRET_KEY !== '') {
                $recaptchaToken = $payload['g-recaptcha-response'] ?? '';
                if (!$this->reCaptchaValidator->validate($recaptchaToken)) {
                    throw new ValidationException(['recaptcha' => 'Please complete the reCAPTCHA verification.']);
                }
            }

            $command = $this->registerValidator->validate($payload);

            $this->registerHandler->handle($command);

            $username = trim($payload['username'] ?? '');
            if ($this->notificationService) {
                $this->notificationService->notifyAllAdmins(
                    'New user "' . $username . '" has registered and is awaiting email verification.',
                    'user_registered',
                    '/notifications'
                );
            }

            $_SESSION['success'] = 'Please check your email for a verification link before logging in.';
            redirect('/login');
            exit;
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $payload;

            redirect('/register');
            exit;
        }
    }

    public function authenticate(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $payload = $_POST;

        try {
            if ($this->reCaptchaValidator && RECAPTCHA_SECRET_KEY !== '') {
                $recaptchaToken = $payload['g-recaptcha-response'] ?? '';
                if (!$this->reCaptchaValidator->validate($recaptchaToken)) {
                    throw new ValidationException(['recaptcha' => 'Please complete the reCAPTCHA verification.']);
                }
            }

            $command = $this->loginValidator->validate($payload);
            $user = $this->loginHandler->handle($command);

            $user->setLogin(true);
            $user->setUpdatedAt($this->nowInMyanmarTime());
            $this->userRepository->update($user);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_role'] = $user->getType()->getValue();

            $_SESSION['user_permissions'] = $this->loadUserPermissions($user->getType()->getValue());

            $_SESSION['user'] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'role' => ucfirst($user->getType()->getValue()),
                'avatar' => $user->getProfileImage()
            ];

            $this->activityRepository->logActivity(
                ucfirst($user->getType()->getValue())
                    . ' "'
                    . $user->getUsername()
                    . '" logged into the system.',
                $user->getId(),
                strtoupper($user->getType()->getValue())
            );

            if ($this->notificationService) {
                $this->notificationService->notifyAllAdmins(
                    ucfirst($user->getType()->getValue())
                        . ' "'
                        . $user->getUsername()
                        . '" logged into the system.',
                    'user_login',
                    '/notifications'
                );
            }

            $this->redirectByRole($user);
            exit;
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $payload;

            redirect('/login');
            exit;
        }
    }

    public function logout(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $user = $this->userRepository->findById((int) $_SESSION['user_id']);

            if ($user !== null) {
                $role = $_SESSION['user_role'] ?? 'unknown';
                $username = $user->getUsername();
                $this->activityRepository->logActivity(
                    "{$role} \"{$username}\" logged out of the system.",
                    (int) $_SESSION['user_id'],
                    $role
                );

                if ($this->notificationService) {
                    $this->notificationService->notifyAllAdmins(
                        ucfirst($role) . ' "' . $username . '" logged out of the system.',
                        'user_logout',
                        '/notifications'
                    );
                }

                $user->setLogin(false);
                $user->setUpdatedAt($this->nowInMyanmarTime());
                $this->userRepository->update($user);
            }
        }

        session_unset();
        session_destroy();

        redirect('/login');
    }

    public function verifyEmail(): void
    {
        $token = trim($_GET['token'] ?? '');
        $uid = isset($_GET['uid']) ? (int) $_GET['uid'] : 0;

        if ($token === '') {
            View::render('auth/verification_failed', [
                'message' => 'Invalid verification link.'
            ], '_standalone_');
            return;
        }

        $user = $this->userRepository->findByVerificationToken($token);

        if ($user === null && $uid > 0) {
            $user = $this->userRepository->findById($uid);
            if ($user !== null && $user->getVerificationToken() !== $token) {
                $user = null;
            }
        }

        if ($user === null) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link not found.'
            ], '_standalone_');
            return;
        }

        if ($user->isVerified()) {
            View::render('auth/verification_success', [], '_standalone_');
            return;
        }

        $expireAt = $user->getVerificationTokenExpireAt();

        if ($expireAt !== null && $expireAt < new \DateTimeImmutable()) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link has expired.'
            ], '_standalone_');
            return;
        }

        $user->setStatus(\App\Domain\UserManagement\ValueObjects\UserStatus::active());
        $user->setVerified(true);
        $user->setEmailVerifiedAt($this->nowInMyanmarTime());

        $this->userRepository->update($user);

        View::render('auth/verification_success', [], '_standalone_');
    }

    private function nowInMyanmarTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now', new \DateTimeZone('Asia/Yangon'));
    }

    private function loadUserPermissions(string $roleCode): array
    {
        $roles = $this->roleRepo->findAll();
        foreach ($roles as $role) {
            if (strcasecmp($role->getCode(), $roleCode) === 0) {
                $permissions = $this->permRepo->findPermissionsByUserTypeId($role->getId());
                return array_map(fn($p) => $p->getKey(), $permissions);
            }
        }

        return [];
    }

    private function redirectByRole(User $user): void
    {
        $dest = match ($user->getType()->getValue()) {
            'farmer' => '/',
            'expert' => '/dashboard',
            'admin' => '/dashboard',
        };

        redirect($dest);
    }
}
