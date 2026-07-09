<?php

namespace App\Presentation\Controllers\Admin;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;

class ConsultationController
{
    use AuthorizesPermissions;

    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    #[Permission('consultations.view', 'View Consultations')]
    public function index(): void
    {
        $this->authorize('consultations.view');

        $consultations = $this->consultationRepository->findAll();

        View::render('admin/consultations', [
            'consultations' => $consultations,
            'activePage' => 'admin-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.view', 'View Consultations')]
    public function view(): void
    {
        $this->authorize('consultations.view');

        $id = (int) ($_GET['id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            redirect('/admin/consultations');
            return;
        }

        $experts = $this->userRepository->findExperts();
        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        View::render('admin/consultation-view', [
            'consultation' => $consultation,
            'experts' => $experts,
            'farmer' => $farmer,
            'activePage' => 'admin-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.assign', 'Assign Expert')]
    public function assignExpert(): void
    {
        $this->authorize('consultations.assign');

        $id = (int) ($_POST['consultation_id'] ?? 0);
        $expertId = (int) ($_POST['expert_id'] ?? 0);

        if (!$id || !$expertId) {
            $_SESSION['error'] = 'Invalid consultation or expert.';
            redirect('/admin/consultations');
            return;
        }

        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/admin/consultations');
            return;
        }

        $consultation->assignExpert($expertId);
        $this->consultationRepository->update($consultation);

        $_SESSION['success'] = 'Expert assigned successfully.';
        redirect('/admin/consultations');
    }
}
