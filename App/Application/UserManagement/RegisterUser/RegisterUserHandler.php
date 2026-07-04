<?php

namespace App\Application\UserManagement\RegisterUser;

use App\Domain\UserManagement\Entities\EmailVerification;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\EmailVerificationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Infrastructure\Mail\MailServiceInterface;
use App\Shared\Exceptions\ValidationException;

final class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailServiceInterface $mailService
    ) {}

    public function handle(RegisterUserCommand $command): void
    {
        $errors = [];

        if ($this->userRepository->findByUsername($command->username) !== null) {
            $errors['username'] = 'Username is already taken.';
        }

        if ($this->userRepository->findByEmail($command->email) !== null) {
            $errors['email'] = 'Email address is already registered.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        /*
         * |--------------------------------------------------------------------------
         * | Save User (Pending)
         * |--------------------------------------------------------------------------
         */
                $token = bin2hex(random_bytes(32));



$user = new User(
    id: null,
    username: $command->username,
    email: new Email($command->email),
    phoneNumber: $command->phoneNumber,
    address: $command->address,
    passwordHash: $command->passwordHash,
    type: $command->type,
    status: UserStatus::pending(),
    isVerified: false,
    isLogin: false,
    profileImage: null,
    verificationToken: $token,
    verificationTokenExpireAt: new \DateTimeImmutable('+1 day')
);


        $this->userRepository->save($user);

        /*
         * |--------------------------------------------------------------------------
         * | Generate Token
         * |--------------------------------------------------------------------------
         */


        /*
         * |--------------------------------------------------------------------------
         * | Save Verification Record
         * |--------------------------------------------------------------------------
         */


        /*
         * |--------------------------------------------------------------------------
         * | Build Verification URL
         * |--------------------------------------------------------------------------
         */

        $verificationLink =
            APP_URL
            . '/verify-email?token='
            . urlencode($token);

        /*
         * |--------------------------------------------------------------------------
         * | Send Email
         * |--------------------------------------------------------------------------
         */

        $this->mailService->sendVerificationEmail(
            $user->getEmail()->getValue(),
            $user->getUsername(),
            $verificationLink
        );
    }
}
