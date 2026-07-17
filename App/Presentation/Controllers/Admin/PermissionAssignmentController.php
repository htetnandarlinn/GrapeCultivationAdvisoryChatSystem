<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\NotificationManagement\NotificationService;
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
        private NotificationService $notificationService,
    ) {}

    private function notifySelf(string $message, string $type, ?string $link = null): void
    {
        $adminId = (int) ($_SESSION['user_id'] ?? 0);
        if ($adminId > 0) {
            $this->notificationService->notify($adminId, 'admin', $message, $type, $link);
        }
    }

    #[Permission('permissions.sync', 'Sync Permissions')]
    public function sync(): void
    {

        $count = $this->registrar->register();
        $_SESSION['role_message'] = $count > 0
            ? "$count new permission(s) registered from code."
            : 'All permissions are already registered.';
        $this->notifySelf('You synced permissions (' . $count . ' new registered).', 'admin_action', '/admin/roles');
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
            $this->notifySelf('You updated permissions for role #' . $roleId . '.', 'admin_action', '/admin/roles');
        } catch (\RuntimeException $e) {
            $_SESSION['role_message'] = $e->getMessage();
        }

        redirect('/admin/roles');
    }
}
