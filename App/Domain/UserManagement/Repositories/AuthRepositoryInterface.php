<?php

namespace App\Domain\UserManagement\Repositories;

use App\Domain\UserManagement\Entities\User;

interface AuthRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?User;

    public function verifyCredentials(string $identifier, string $password): bool;
}
