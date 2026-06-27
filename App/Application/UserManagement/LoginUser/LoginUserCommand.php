<?php

namespace App\Application\UserManagement\LoginUser;

final class LoginUserCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
