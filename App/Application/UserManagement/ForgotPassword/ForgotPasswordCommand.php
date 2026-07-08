<?php

namespace App\Application\UserManagement\ForgotPassword;

final class ForgotPasswordCommand
{
    public function __construct(
        public readonly string $email
    ) {}
}
