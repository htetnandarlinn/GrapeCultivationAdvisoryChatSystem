<?php

namespace App\Domain\UserManagement\Services;

use App\Domain\UserManagement\Entities\User;

final class UserDomainService
{
    public function canRegister(User $user): bool
    {
        return $user->getStatus()->getValue() !== 'blocked';
    }
}

