<?php

namespace App\Application\UserManagement\RegisterUser;

use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Infrastructure\Mail\MailServiceInterface;
use App\Shared\Exceptions\ValidationException;

final class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private MailServiceInterface $mailService,
        private ActivityRepositoryInterface $activityRepository,
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

        $role = ucfirst($user->getType()->getValue());

        $this->activityRepository->logActivity(
            $role . ' "' . $user->getUsername() . '" registered successfully.',
            $user->getId(),
            strtoupper($user->getType()->getValue())
        );

        $this->activityRepository->logActivity(
            'A new ' . strtolower($role) . ' account has been registered successfully.',
            $user->getId(),
            strtoupper($user->getType()->getValue())
        );

        $verificationLink =
            APP_URL
            . '/verify-email?token='
            . urlencode($token);

        $this->mailService->sendVerificationEmail(
            $user->getEmail()->getValue(),
            $user->getUsername(),
            $verificationLink
        );
    }
}
