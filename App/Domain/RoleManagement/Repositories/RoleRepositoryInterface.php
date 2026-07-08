<?php

namespace App\Domain\RoleManagement\Repositories;

use App\Domain\RoleManagement\Entities\Role;

interface RoleRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Role;

    public function existsByCode(string $code, ?int $excludeId = null): bool;

    public function create(string $code, string $label): int;

    public function update(int $id, string $code, string $label): void;

    public function delete(int $id): void;
}
