<?php

namespace App\Domain\UserManagement\Services;

use App\Domain\UserManagement\Entities\User;
use App\Shared\Exceptions\ValidationException;

final class UserAuthenticationService
{
    public function ensureCanAuthenticate(User $user): void
    {
        if ($user->getStatus()->getValue() === 'blocked') {
            throw new ValidationException(['login' => 'Your account is blocked.']);
        }
    }
}
