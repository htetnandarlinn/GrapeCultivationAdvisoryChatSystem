<?php

namespace App\Application\UserManagement\VerifyEmail;

use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\UserStatus;

final class VerifyEmailHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EmailVerificationRepositoryInterface $verificationRepository
    ) {
    }

    public function handle(VerifyEmailCommand $command): void
    {
        /*
        |--------------------------------------------------------------------------
        | Find verification record
        |--------------------------------------------------------------------------
        */

        $verification = $this->verificationRepository
            ->findByToken($command->token);

        if ($verification === null) {
            throw new \Exception('Invalid verification link.');
        }

        /*
        |--------------------------------------------------------------------------
        | Already verified?
        |--------------------------------------------------------------------------
        */

        if ($verification->isVerified()) {
            throw new \Exception('Email has already been verified.');
        }

        /*
        |--------------------------------------------------------------------------
        | Expired?
        |--------------------------------------------------------------------------
        */

        if ($verification->isExpired()) {
            throw new \Exception('Verification link has expired.');
        }

        /*
        |--------------------------------------------------------------------------
        | Find user
        |--------------------------------------------------------------------------
        */

        $user = $this->userRepository
            ->findById($verification->getUserId());

        if ($user === null) {
            throw new \Exception('User not found.');
        }

        /*
        |--------------------------------------------------------------------------
        | Activate account
        |--------------------------------------------------------------------------
        */

        $user->setStatus(UserStatus::active());

        $this->userRepository->update($user);

        /*
        |--------------------------------------------------------------------------
        | Mark verification as complete
        |--------------------------------------------------------------------------
        */

        $verification->markAsVerified();

        $this->verificationRepository->update($verification);

        /*
        |--------------------------------------------------------------------------
        | Optional cleanup
        |--------------------------------------------------------------------------
        */

        $this->verificationRepository
            ->deleteByUserId($user->getId());
    }
}