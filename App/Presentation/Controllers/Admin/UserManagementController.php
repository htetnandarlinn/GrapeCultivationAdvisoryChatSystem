<?php

namespace App\Presentation\Controllers\Admin;

use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\AdminView;

final class UserManagementController
{
    use AuthorizesPermissions;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepo,
    ) {}

    #[Permission('admin.users.view', 'View Users')]
    public function index(): void
    {
        $this->authorize('admin.users.view');

        if (!isset($_SESSION['admin_name'])) {
            $_SESSION['admin_name'] = $_SESSION['user']['username'] ?? 'Admin';
        }

        $farmers = $this->userRepository->findFarmers();
        $experts = $this->userRepository->findExperts();

        $allUsers = array_merge($farmers, $experts);
        usort($allUsers, fn($a, $b) => $b->getCreatedAt() <=> $a->getCreatedAt());

        $roles = $this->roleRepo->findAll();

        AdminView::render('admin/userManagement', [
            'activePage' => 'users',
            'allUsers' => $allUsers,
            'farmers' => $farmers,
            'experts' => $experts,
            'farmerCount' => count($farmers),
            'expertCount' => count($experts),
            'roles' => $roles,
        ]);
    }

    #[Permission('admin.users.role', 'Assign User Role')]
    public function assignRole(): void
    {
        $this->authorize('admin.users.role');

        $userId = (int) ($_POST['user_id'] ?? 0);
        $roleCode = strtolower(trim($_POST['role_code'] ?? ''));

        if ($userId <= 0 || $roleCode === '') {
            $_SESSION['admin_message'] = 'Invalid user or role.';
            redirect('/admin/users');
            return;
        }

        $user = $this->userRepository->findById($userId);
        if ($user === null) {
            $_SESSION['admin_message'] = 'User not found.';
            redirect('/admin/users');
            return;
        }

        $newType = match ($roleCode) {
            'farmer' => UserType::farmer(),
            'expert' => UserType::expert(),
            'admin' => UserType::admin(),
            default => null,
        };

        if ($newType === null) {
            $_SESSION['admin_message'] = 'Invalid role.';
            redirect('/admin/users');
            return;
        }

        $user->setType($newType);

        $this->userRepository->update($user);

        $_SESSION['admin_message'] = 'User role updated successfully.';
        redirect('/admin/users');
    }
}
