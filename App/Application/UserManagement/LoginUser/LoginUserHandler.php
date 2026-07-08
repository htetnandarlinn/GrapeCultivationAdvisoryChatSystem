<?php

namespace App\Application\UserManagement\LoginUser;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\AuthRepositoryInterface;
use App\Domain\UserManagement\Services\UserAuthenticationService;
use App\Shared\Exceptions\ValidationException;
use App\Shared\Helpers\ValidationHelper;

final class LoginUserHandler
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private UserAuthenticationService $authenticationService,
    ) {
    }

    public function handle(LoginUserCommand $command): User
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Input
        |--------------------------------------------------------------------------
        */

        ValidationHelper::assertNotEmpty(
            $command->email,
            'Username or Email is required.'
        );

        ValidationHelper::assertNotEmpty(
            $command->password,
            'Password is required.'
        );

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $user = $this->authRepository->findByIdentifier(
            $command->email
        );

        if ($user === null) {
            throw new ValidationException([
                'username_or_email' =>
                    'Incorrect username or email.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Password
        |--------------------------------------------------------------------------
        */

        if (
            !$this->authRepository->verifyCredentials(
                $command->email,
                $command->password
            )
        ) {
            throw new ValidationException([
                'password' =>
                    'Incorrect password.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Email Verification
        |--------------------------------------------------------------------------
        */

        if (!$user->isVerified()) {
            throw new ValidationException([
                'email' =>
                    'Please verify your email before logging in.'
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Other Account Checks
        |--------------------------------------------------------------------------
        */

        $this->authenticationService
            ->ensureCanAuthenticate($user);

        return $user;
    }
}