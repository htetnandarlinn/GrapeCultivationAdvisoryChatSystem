<?php

namespace App\Application\UserManagement\ResetPassword;

final class ResetPasswordCommand
{
    public function __construct(
        public readonly string $token,
        public readonly string $password
    ) {}
}
