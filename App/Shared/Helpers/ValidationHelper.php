<?php

namespace App\Shared\Helpers;

use App\Shared\Exceptions\ValidationException;

final class ValidationHelper
{
    public static function assertNotEmpty(?string $value, string $message): void
    {
        if (trim((string) $value) === '') {
            throw new ValidationException(['login' => $message]);
        }
    }
}

