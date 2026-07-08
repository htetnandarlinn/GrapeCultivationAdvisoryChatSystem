<?php

namespace App\Presentation\Controllers\Admin;

use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\AdminView;

class AdminDashboardController
{
    use AuthorizesPermissions;

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ActivityRepositoryInterface $activityRepository,
    ) {}

    #[Permission('admin.dashboard.view', 'View Admin Dashboard')]
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        if (!isset($_SESSION['admin_name'])) {
            $_SESSION['admin_name'] = $_SESSION['user']['username'] ?? 'Admin';
        }

        $farmerCount = $this->userRepository->countFarmers();
        $expertCount = $this->userRepository->countExperts();

        $activities = $this->activityRepository->getRecentActivities(8);

        AdminView::render('admin/admin-dashboard', [
            'activePage'   => 'dashboard',
            'farmerCount'  => $farmerCount,
            'expertCount'  => $expertCount,
            'activities'   => $activities,
        ]);
    }
}
