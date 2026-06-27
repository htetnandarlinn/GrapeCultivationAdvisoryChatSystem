<?php

namespace App\Presentation\Controllers;

use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Domain\UserManagement\Entities\User;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Shared\Exceptions\ValidationException;

final class AuthController
{
    public function __construct(
        private RegisterUserHandler $registerHandler,
        private LoginUserHandler $loginHandler,
        private RegisterRequestValidator $registerValidator,
        private LoginRequestValidator $loginValidator,
    ) {}

    public function register(array $payload): void
    {
        try {
            $command = $this->registerValidator->validate($payload);
            $this->registerHandler->handle($command);

            $_SESSION['success'] = 'Registration successful!';
            unset($_SESSION['errors'], $_SESSION['old']);

            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;

        } catch (ValidationException $e) {

            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $payload;

            header('Location: ' . BASE_URL . '/index.php?page=register');
            exit;
        }
    }

    public function authenticate(array $payload): void
    {
        try {
            $command = $this->loginValidator->validate($payload);
            $user = $this->loginHandler->handle($command);

            $_SESSION['user_id'] = $user->getId();
            $_SESSION['user_role'] = $user->getType()->getValue();
            $_SESSION['username'] = $user->getUsername();
            unset($_SESSION['errors'], $_SESSION['old']);

            $destination = $this->mapRoleToDashboard($user);
            header('Location: ' . BASE_URL . '/index.php?page=' . ltrim($destination, '/'));
            exit;

        } catch (ValidationException $e) {

            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $payload;

            header('Location: ' . BASE_URL . '/index.php?page=login');
            exit;
        }
    }

    private function mapRoleToDashboard(User $user): string
    {
        return match ($user->getType()->getValue()) {
            'admin' => '/admin-dashboard',
            'expert' => '/expert-dashboard',
            default => '/farmer-dashboard',
        };
    }
}