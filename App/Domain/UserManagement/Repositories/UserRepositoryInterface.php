<?php

namespace App\Domain\UserManagement\Repositories;

use App\Domain\UserManagement\Entities\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function update(User $user): void;

    public function findById(int $id): ?User;

    public function findByVerificationToken(string $token): ?User;

    public function findByEmail(string $email): ?User;

    public function findByGoogleId(string $googleId): ?User;

    public function findByUsername(string $username): ?User;

    public function findByUsernameOrEmail(string $identifier): ?User;

    public function findFarmers(): array;

    public function countFarmers(): int;

    public function findExperts(): array;

    public function countExperts(): int;

    public function deleteById(int $id): void;

    public function emailExists(string $email): bool;

    // updatestatue
    public function updateStatus(
        int $userId,
        string $status
    ): void;
}
