<?php

namespace App\Domain\UserManagement\Services;

use App\Domain\UserManagement\Entities\User;
use App\Shared\Exceptions\ValidationException;

final class UserAuthenticationService
{
    public function ensureCanAuthenticate(User $user): void
    {
        $status = $user->getStatus();

        if ($status->isPending()) {
            throw new ValidationException([
                'login' => 'Please verify your email before logging in.'
            ]);
        }

        if ($status->isInactive()) {
            throw new ValidationException([
                'login' => 'Your account is inactive.'
            ]);
        }

        if ($status->isBlocked()) {
            throw new ValidationException([
                'login' => 'Your account has been blocked.'
            ]);
        }
    }
}