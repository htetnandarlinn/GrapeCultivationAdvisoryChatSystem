<?php

namespace App\Domain\UserManagement\Repositories;

use App\Domain\UserManagement\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function findByUsername(string $username): ?User;

    public function findByUsernameOrEmail(string $identifier): ?User;

    public function emailExists(string $email): bool;
}