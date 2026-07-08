<?php

namespace App\Presentation\Controllers\Admin;

use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Views\AdminView;
use App\Infrastructure\Persistence\Repositories\ActivityRepository;

class FarmerManagementController
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

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

        $message = $_SESSION['admin_message'] ?? null;
        unset($_SESSION['admin_message']);

        AdminView::render('admin/farmerManagement', [
            'activePage' => 'farmers',
            'farmers' => $this->userRepository->findFarmers(),
            'message' => $message,
        ]);
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

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

    if ($id <= 0) {
        redirect('/admin/farmers');
        return;
    }

    $farmer = $this->userRepository->findById($id);

    if ($farmer === null) {
        redirect('/admin/farmers');
        return;
    }

    $this->userRepository->deleteById($id);

    $activityRepository = new ActivityRepository();

    $activityRepository->logActivity(
        'Administrator deleted farmer "' . $farmer->getUsername() . '".',
        $_SESSION['user']['id'] ?? null,
        'ADMIN'
    );

    $_SESSION['admin_message'] = 'Farmer record deleted successfully.';

    redirect('/admin/farmers');
}

    public function view(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if ($id <= 0) {
            redirect('/admin/farmers');
            return;
        }

        $farmer = $this->userRepository->findById($id);
        if ($farmer === null || $farmer->getType()->getValue() !== 'farmer') {
            redirect('/admin/farmers');
            return;
        }

        AdminView::render('admin/farmer-view', [
            'activePage' => 'farmers',
            'farmer' => $farmer,
        ]);
    }
}
