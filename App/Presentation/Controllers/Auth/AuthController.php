<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\Entities\User;
use App\Infrastructure\Persistence\Repositories\PermissionRepository;
use App\Infrastructure\Persistence\Repositories\RoleRepository;
use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Views\View;
use App\Shared\Exceptions\ValidationException;

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
            $command = $this->registerValidator->validate($payload);

            $this->registerHandler->handle($command);

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
            $command = $this->loginValidator->validate($payload);
            $user = $this->loginHandler->handle($command);

            $user->setLogin(true);
            $user->setUpdatedAt($this->nowInMyanmarTime());
            $this->userRepository->update($user);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_role'] = $user->getType()->getValue();

            if ($user->getType()->getValue() !== 'admin') {
                $_SESSION['user_permissions'] = $this->loadUserPermissions($user->getType()->getValue());
            }

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

        if ($token === '') {
            View::render('auth/verification_failed', [
                'message' => 'Invalid verification link.'
            ], '_standalone_');
            return;
        }

        $user = $this->userRepository->findByVerificationToken($token);

        if ($user === null) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link not found.'
            ], '_standalone_');
            return;
        }

        if ($user->isVerified()) {
            View::render('auth/verification_failed', [
                'message' => 'Email already verified.'
            ], '_standalone_');
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
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpireAt(null);
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
            if ($role->getCode() === $roleCode) {
                $permissions = $this->permRepo->findPermissionsByUserTypeId($role->getId());
                return array_map(fn($p) => $p->getKey(), $permissions);
            }
        }

        return [];
    }

    private function redirectByRole(User $user): void
    {
        if ($user->getType()->getValue() === 'admin') {
            redirect('/dashboard');
        } else {
            redirect('/');
        }
    }
}
