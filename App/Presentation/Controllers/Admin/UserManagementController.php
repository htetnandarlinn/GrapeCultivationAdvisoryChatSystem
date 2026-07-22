<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\NotificationManagement\NotificationService;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

final class UserManagementController
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
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

    #[Permission('users.view', 'View Users')]
    public function index(): void
    {

        if (!isset($_SESSION['admin_name'])) {
            $_SESSION['admin_name'] = $_SESSION['user']['username'] ?? 'Admin';
        }

        $farmers = $this->userRepository->findFarmers();
        $experts = $this->userRepository->findExperts();

        $allUsers = array_merge($farmers, $experts);
        usort($allUsers, fn($a, $b) => $b->getCreatedAt() <=> $a->getCreatedAt());

        $roles = $this->roleRepo->findAll();

        View::render('admin/userManagement', [
            'activePage' => 'users',
            'allUsers' => $allUsers,
            'farmers' => $farmers,
            'experts' => $experts,
            'farmerCount' => count($farmers),
            'expertCount' => count($experts),
            'roles' => $roles,
        ]);
    }

    #[Permission('users.role', 'Assign User Role')]
    public function assignRole(): void
    {


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
        $this->notifySelf('You updated the role of ' . $user->getUsername() . ' to "' . ucfirst($roleCode) . '".', 'admin_action', '/admin/users');
        redirect('/admin/users');
    }
}
