<?php

namespace App\Domain\UserManagement\Repositories;

use App\Domain\UserManagement\Entities\PasswordReset;

interface PasswordResetRepositoryInterface
{
    public function save(PasswordReset $reset): void;

    public function findByToken(string $token): ?PasswordReset;

    public function findLatestByUserId(int $userId): ?PasswordReset;

    public function deleteByUserId(int $userId): void;
}
