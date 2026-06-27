<?php

namespace App\Application\UserManagement\RegisterUser;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Shared\Exceptions\ValidationException;
use App\Application\UserManagement\RegisterUser\RegisterUserCommand;
final class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository 
    ) {
    }
 
    public function handle(RegisterUserCommand $command): void
    {
        $errors = [];

        // Check whether the username/full name already exists
        if ($this->userRepository->findByUsername($command->name) !== null) {
            $errors['name'] = 'Username is already taken.';
        }

        // Check whether the email already exists
        if ($this->userRepository->findByEmail($command->email) !== null) {
            $errors['email'] = 'Email address is already registered.';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }

        $user = new User(
            id: $command->id,
            username: $command->username,
            name: $command->name,
            email: new Email($command->email),
            phoneNumber: $command->phoneNumber,
            address: $command->address,
            passwordHash: $command->passwordHash,
            type: $command->type,
            status: UserStatus::active()
        );

        $this->userRepository->save($user);
    }
}