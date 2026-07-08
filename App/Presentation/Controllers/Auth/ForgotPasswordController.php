<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\ForgotPassword\ForgotPasswordCommand;
use App\Application\UserManagement\ForgotPassword\ForgotPasswordHandler;
use App\Application\UserManagement\ResetPassword\ResetPasswordCommand;
use App\Application\UserManagement\ResetPassword\ResetPasswordHandler;
use App\Domain\UserManagement\Repositories\PasswordResetRepositoryInterface;
use App\Presentation\Views\View;

class ForgotPasswordController
{
    public function __construct(
        private ForgotPasswordHandler $forgotHandler,
        private ResetPasswordHandler $resetHandler,
        private PasswordResetRepositoryInterface $passwordResetRepo,
    ) {}

    public function showForm(): void
    {
        View::render('auth/forgot-password', []);
    }

    public function send(): void
    {
        $email = trim($_POST['email'] ?? '');

        $command = new ForgotPasswordCommand($email);

        $result = $this->forgotHandler->handle($command);

        if (!$result) {
            $_SESSION['errors'] = ['email' => 'Email not found.'];
            redirect('/forgot-password');
            return;
        }

        $_SESSION['success'] = 'If the email exists, a password reset link has been sent.';
        redirect('/login');
    }

    public function showReset(): void
    {
        $token = $_GET['token'] ?? '';

        $reset = $this->passwordResetRepo->findByToken($token);

        if ($reset === null || $reset->isExpired()) {
            View::render('auth/invalid-token', []);
            return;
        }

        View::render('auth/reset-password', ['token' => htmlspecialchars($token)]);
    }

    public function reset(): void
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($password !== $passwordConfirm) {
            $_SESSION['errors'] = ['password' => 'Passwords do not match.'];
            redirect('/reset-password?token=' . urlencode($token));
            return;
        }

        $errors = [];
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $password) || !preg_match('/\d/', $password)) {
            $errors['password'] = 'Password must include both letters and numbers.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            redirect('/reset-password?token=' . urlencode($token));
            return;
        }

        $command = new ResetPasswordCommand($token, $password);

        $ok = $this->resetHandler->handle($command);

        if (!$ok) {
            View::render('auth/invalid-token', []);
            return;
        }

        $_SESSION['success'] = 'Password updated. You may now login.';
        redirect('/login');
    }
}
