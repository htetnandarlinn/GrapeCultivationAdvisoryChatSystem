<?php

namespace App\Domain\PermissionManagement\Repositories;

use App\Domain\PermissionManagement\Entities\Permission;

interface PermissionRepositoryInterface
{
    public function findAll(): array;

    public function existsByKey(string $key): bool;

    public function create(string $key, string $name, string $description = ''): int;

    public function findPermissionsByUserTypeId(int $userTypeId): array;

    public function assignPermissionsToUserType(int $userTypeId, array $permissionIds): void;

    public function getAssignedPermissionIds(int $userTypeId): array;
}
