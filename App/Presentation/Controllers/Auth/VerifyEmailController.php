<?php

namespace App\Presentation\Controllers\Auth;

use App\Domain\UserManagement\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Presentation\Views\View;

final class VerifyEmailController
{
    public function __construct(
        private EmailVerificationRepositoryInterface $verificationRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function verify(): void
    {
        $token = trim($_GET['token'] ?? '');

        if ($token === '') {
            View::render('auth/verification_failed', [
                'message' => 'Invalid verification link.'
            ], '_standalone_');
            return;
        }

        $verification = $this->verificationRepository->findByToken($token);

        if ($verification === null) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link not found.'
            ], '_standalone_');
            return;
        }

        if ($verification->isVerified()) {
            View::render('auth/verification_failed', [
                'message' => 'Email already verified.'
            ], '_standalone_');
            return;
        }

        if ($verification->isExpired()) {
            View::render('auth/verification_failed', [
                'message' => 'Verification link has expired.'
            ], '_standalone_');
            return;
        }

        $verification->markAsVerified();

        $this->verificationRepository->update($verification);

        $this->userRepository->updateStatus(
            $verification->getUserId(),
            UserStatus::active()->getValue()
        );

        View::render('auth/verification_success', [], '_standalone_');
    }
}
