<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\NotificationManagement\NotificationService;
use App\Application\RoleManagement\RoleService;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

class RoleController
{
    public function __construct(
        private RoleRepositoryInterface $repository,
        private RoleService $service,
        private NotificationService $notificationService,
    ) {}

    private function notifySelf(string $message, string $type, ?string $link = null): void
    {
        $adminId = (int) ($_SESSION['user_id'] ?? 0);
        if ($adminId > 0) {
            $this->notificationService->notify($adminId, 'admin', $message, $type, $link);
        }
    }

    #[Permission('roles.view', 'View Roles')]
    public function index(): void
    {
        $message = $_SESSION['role_message'] ?? null;
        unset($_SESSION['role_message']);

        View::render('admin/roles', [
            'activePage' => 'roles',
            'roles' => $this->repository->findAll(),
            'message' => $message,
        ]);
    }

    #[Permission('roles.create', 'Create Role')]
    public function create(): void
    {

        View::render('admin/role-form', [
            'activePage' => 'roles',
            'mode' => 'create',
            'formAction' => '/admin/roles/store',
            'submitLabel' => 'Create Role',
        ]);
    }

    #[Permission('roles.create', 'Create Role')]
    public function store(): void
    {

        $name = trim($_POST['name'] ?? '');

        if ($name === '') {
            $_SESSION['errors'] = ['name' => 'Role name is required.'];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/create');
            return;
        }

        try {
            $this->service->create($name);
            $_SESSION['role_message'] = 'Role created successfully.';
            $this->notifySelf('You created a new role "' . $name . '".', 'admin_action', '/admin/roles');
            redirect('/admin/roles');
        } catch (\RuntimeException $e) {
            $_SESSION['errors'] = ['name' => $e->getMessage()];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/create');
        }
    }

    #[Permission('roles.edit', 'Edit Role')]
    public function edit(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            redirect('/admin/roles');
            return;
        }

        $role = $this->repository->findById($id);

        if ($role === null) {
            $_SESSION['role_message'] = 'Role not found.';
            redirect('/admin/roles');
            return;
        }

        View::render('admin/role-form', [
            'activePage' => 'roles',
            'role' => $role,
            'mode' => 'edit',
            'formAction' => '/admin/roles/update',
            'submitLabel' => 'Update Role',
        ]);
    }

    #[Permission('roles.edit', 'Edit Role')]
    public function update(): void
    {
        $id = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');

        if ($id <= 0) {
            redirect('/admin/roles');
            return;
        }

        if ($name === '') {
            $_SESSION['errors'] = ['name' => 'Role name is required.'];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/edit?id=' . $id);
            return;
        }

        try {
            $this->service->update($id, $name);
            $_SESSION['role_message'] = 'Role updated successfully.';
            $this->notifySelf('You updated the role "' . $name . '".', 'admin_action', '/admin/roles');
            redirect('/admin/roles');
        } catch (\RuntimeException $e) {
            $_SESSION['errors'] = ['name' => $e->getMessage()];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/edit?id=' . $id);
        }
    }

    #[Permission('roles.delete', 'Delete Role')]
    public function delete(): void
    {
        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['role_message'] = 'Invalid role ID.';
            redirect('/admin/roles');
            return;
        }

        $this->service->delete($id);
        $_SESSION['role_message'] = 'Role deleted successfully.';
        $this->notifySelf('You deleted a role (#' . $id . ').', 'admin_action', '/admin/roles');
        redirect('/admin/roles');
    }
}
