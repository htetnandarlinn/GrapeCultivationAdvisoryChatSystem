<?php

namespace App\Presentation\Controllers\Admin;

use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\AdminView;
use App\Infrastructure\Persistence\Repositories\ActivityRepository;

class FarmerManagementController
{
    use AuthorizesPermissions;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    #[Permission('admin.farmers.view', 'View Farmers')]
    public function index(): void
    {
        $this->authorize('admin.farmers.view');

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

   #[Permission('admin.farmers.delete', 'Delete Farmer')]
    public function delete(): void
{
    $this->authorize('admin.farmers.delete');

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

    #[Permission('admin.farmers.view', 'View Farmers')]
    public function view(): void
    {
        $this->authorize('admin.farmers.view');

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
