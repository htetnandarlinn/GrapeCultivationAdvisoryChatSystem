<?php

namespace App\Presentation\Controllers\Auth;

use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Infrastructure\Persistence\Repositories\EmailVerificationRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Views\View;

final class VerifyEmailController
{
    private EmailVerificationRepository $verificationRepository;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->verificationRepository = new EmailVerificationRepository();
        $this->userRepository = new UserRepository();
    }

    public function verify(): void
    {
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            View::render('auth/verification_failed', [
                'message' => 'Invalid verification link.'
            ]);
            return;
        }

        $verification = $this->verificationRepository->findByToken($token);

        if ($verification === null) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link not found.'
            ]);
            return;
        }

        if ($verification->isVerified()) {
            View::render('auth/verification_failed', [
                'message' => 'Email already verified.'
            ]);
            return;
        }

        if ($verification->isExpired()) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link has expired.'
            ]);
            return;
        }

        $verification->markAsVerified();

        $this->verificationRepository->update($verification);

        $this->userRepository->updateStatus(
            $verification->getUserId(),
            UserStatus::active()->getValue()
        );

        View::render('auth/verification_success');

        // OR redirect instead:
        // $_SESSION['success'] = 'Email verified successfully. Please login.';
        // redirect('/login');
    }
}