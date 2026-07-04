<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Services\UserAuthenticationService;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Infrastructure\Mail\PHPMailerService;
use App\Infrastructure\Persistence\Repositories\AuthRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Views\View;
use App\Shared\Exceptions\ValidationException;

final class AuthController
{
    private RegisterUserHandler $registerHandler;
    private LoginUserHandler $loginHandler;
    private RegisterRequestValidator $registerValidator;
    private LoginRequestValidator $loginValidator;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();

        $mailService = new PHPMailerService();

        $this->registerHandler = new RegisterUserHandler(
            $this->userRepository,
            $mailService
        );
        $this->loginHandler = new LoginUserHandler(
            new AuthRepository($this->userRepository),
            new UserAuthenticationService()
        );

        $this->registerValidator = new RegisterRequestValidator();
        $this->loginValidator = new LoginRequestValidator();
    }

    public function showRegister()
    {
        return View::render('auth/register');
    }

    public function showLogin()
    {
        return View::render('auth/login');
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
        $payload = $_POST;

        try {
            $command = $this->loginValidator->validate($payload);
            $user = $this->loginHandler->handle($command);

            $user->setLogin(true);
            $user->setUpdatedAt($this->nowInMyanmarTime());
            $this->userRepository->update($user);

            // Keep existing session variables
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_role'] = $user->getType()->getValue();

            // Session array for topbar/dashboard
            $_SESSION['user'] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'role' => ucfirst($user->getType()->getValue()),
                'avatar' => $user->getProfileImage()
            ];

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
            ]);
            return;
        }

        $user = $this->userRepository->findByVerificationToken($token);

        if ($user === null) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link not found.'
            ]);
            return;
        }

        if ($user->isVerified()) {
            View::render('auth/verification_failed', [
                'message' => 'Email already verified.'
            ]);
            return;
        }

        $expireAt = $user->getVerificationTokenExpireAt();

        if ($expireAt !== null && $expireAt < new \DateTimeImmutable()) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link has expired.'
            ]);
            return;
        }

        $user->setStatus(UserStatus::active());
        $user->setVerified(true);
        $user->setVerificationToken(null);
        $user->setVerificationTokenExpireAt(null);
        $user->setEmailVerifiedAt($this->nowInMyanmarTime());

        $this->userRepository->update($user);

        View::render('auth/verification_success');
    }

    private function nowInMyanmarTime(): \DateTimeImmutable
    {
        $dateTime = new \DateTimeImmutable('now', new \DateTimeZone('Asia/Yangon'));

        return $dateTime;
    }

    private function redirectByRole(User $user): void
    {
        $route = match ($user->getType()->getValue()) {
            'admin' => '/admin-dashboard',
            'expert' => '/expert-dashboard',
            default => '/farmer-dashboard',
        };

        redirect($route);
    }
}
