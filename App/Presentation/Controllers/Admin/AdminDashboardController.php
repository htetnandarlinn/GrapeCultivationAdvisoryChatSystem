<?php

namespace App\Presentation\Controllers\Admin;

use App\Infrastructure\Persistence\Repositories\ActivityRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Views\AdminView;

class AdminDashboardController
{
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

        // Get dashboard statistics
        $userRepository = new UserRepository();
        $farmerCount = $userRepository->countFarmers();
        $expertCount = $userRepository->countExperts();

        // Get recent system activities
        $activityRepository = new ActivityRepository();
        $activities = $activityRepository->getRecentActivities(8);

        // Render dashboard
        AdminView::render('admin/admin-dashboard', [
            'activePage'   => 'dashboard',
            'farmerCount'  => $farmerCount,
            'expertCount'  => $expertCount,
            'activities'   => $activities,
        ]);
    }
}