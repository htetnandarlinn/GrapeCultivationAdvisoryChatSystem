<?php

namespace App\Application\PermissionManagement;

use App\Domain\PermissionManagement\Repositories\PermissionRepositoryInterface;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;

final class PermissionService
{
    public function __construct(
        private PermissionRepositoryInterface $permissionRepo,
        private RoleRepositoryInterface $roleRepo,
    ) {}

    public function getAllPermissions(): array
    {
        return $this->permissionRepo->findAll();
    }

    public function getGroupedPermissions(): array
    {
        $permissions = $this->permissionRepo->findAll();
        $groups = [];

        foreach ($permissions as $perm) {
            $parts = explode('.', $perm->getKey());
            $module = $parts[0];
            $label = ucfirst($module);

            $groups[$module]['label'] = $label;
            $groups[$module]['permissions'][] = $perm;
        }

        ksort($groups);
        return array_values($groups);
    }

    public function getAssignedForRole(int $roleId): array
    {
        $role = $this->roleRepo->findById($roleId);
        if ($role === null) {
            return [];
        }
        return $this->permissionRepo->getAssignedPermissionIds($roleId);
    }

    public function assignPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->roleRepo->findById($roleId);
        if ($role === null) {
            throw new \RuntimeException('Role not found.');
        }
        $this->permissionRepo->assignPermissionsToUserType($roleId, $permissionIds);
    }
}
