<?php

namespace App\Domain\UserManagement\Events;

final class UserRegistered
{
    public function __construct(public readonly string $userId)
    {
    }
}

