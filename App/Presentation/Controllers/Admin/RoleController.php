<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\RoleManagement\RoleService;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use App\Presentation\Views\AdminView;

class RoleController
{
    public function __construct(
        private RoleRepositoryInterface $repository,
        private RoleService $service,
    ) {}

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        $message = $_SESSION['role_message'] ?? null;
        unset($_SESSION['role_message']);

        AdminView::render('admin/roles', [
            'activePage' => 'roles',
            'roles' => $this->repository->findAll(),
            'message' => $message,
        ]);
    }

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        AdminView::render('admin/role-form', [
            'activePage' => 'roles',
            'mode' => 'create',
            'formAction' => '/admin/roles/store',
            'submitLabel' => 'Create Role',
        ]);
    }

    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

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
            redirect('/admin/roles');
        } catch (\RuntimeException $e) {
            $_SESSION['errors'] = ['name' => $e->getMessage()];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/create');
        }
    }

    public function edit(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

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

        AdminView::render('admin/role-form', [
            'activePage' => 'roles',
            'role' => $role,
            'mode' => 'edit',
            'formAction' => '/admin/roles/update',
            'submitLabel' => 'Update Role',
        ]);
    }

    public function update(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

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
            redirect('/admin/roles');
        } catch (\RuntimeException $e) {
            $_SESSION['errors'] = ['name' => $e->getMessage()];
            $_SESSION['old'] = ['name' => $name];
            redirect('/admin/roles/edit?id=' . $id);
        }
    }

    public function delete(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['role_message'] = 'Invalid role ID.';
            redirect('/admin/roles');
            return;
        }

        $this->service->delete($id);
        $_SESSION['role_message'] = 'Role deleted successfully.';
        redirect('/admin/roles');
    }
}
