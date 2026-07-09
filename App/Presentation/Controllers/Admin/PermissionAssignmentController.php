<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\PermissionManagement\PermissionRegistrar;
use App\Application\PermissionManagement\PermissionService;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

final class PermissionAssignmentController
{
    public function __construct(
        private PermissionService $permissionService,
        private PermissionRegistrar $registrar,
        private RoleRepositoryInterface $roleRepo,
    ) {}

    #[Permission('permissions.sync', 'Sync Permissions')]
    public function sync(): void
    {

        $count = $this->registrar->register();
        $_SESSION['role_message'] = $count > 0
            ? "$count new permission(s) registered from code."
            : 'All permissions are already registered.';
        redirect('/admin/roles');
    }

    #[Permission('permissions.assign', 'Assign Permissions')]
    public function list(): void
    {
        

        $roleId = (int) ($_GET['role_id'] ?? 0);
        $role = $this->roleRepo->findById($roleId);

        if ($role === null) {
            $_SESSION['role_message'] = 'Role not found.';
            redirect('/admin/roles');
            return;
        }

        View::render('admin/permission-assignment', [
            'activePage' => 'roles',
            'role' => $role,
            'permissionGroups' => $this->permissionService->getGroupedPermissions(),
            'allPermissions' => $this->permissionService->getAllPermissions(),
            'assignedIds' => $this->permissionService->getAssignedForRole($roleId),
        ]);
    }

    #[Permission('permissions.assign', 'Assign Permissions')]
    public function update(): void
    {
        

        $roleId = (int) ($_POST['role_id'] ?? 0);
        $permissionIds = array_map('intval', $_POST['permissions'] ?? []);

        if ($roleId <= 0) {
            redirect('/admin/roles');
            return;
        }

        try {
            $this->permissionService->assignPermissions($roleId, $permissionIds);
            $_SESSION['role_message'] = 'Permissions updated successfully.';
        } catch (\RuntimeException $e) {
            $_SESSION['role_message'] = $e->getMessage();
        }

        redirect('/admin/roles');
    }
}
