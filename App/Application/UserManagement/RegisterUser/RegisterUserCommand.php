<?php

namespace App\Application\UserManagement\RegisterUser;

use App\Domain\UserManagement\ValueObjects\UserType;

final class RegisterUserCommand
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $username,
        public readonly string $email,
        public readonly string $phoneNumber,
        public readonly string $address,
        public readonly string $passwordHash,
        public readonly UserType $type
    ) {
    }
}