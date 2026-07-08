<?php

namespace App\Application\UserManagement\VerifyEmail;

final class VerifyEmailCommand
{
    public function __construct(
        public readonly string $token
    ) {}
}