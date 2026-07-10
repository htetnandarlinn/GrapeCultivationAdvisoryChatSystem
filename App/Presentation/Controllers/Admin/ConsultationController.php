<?php

namespace App\Presentation\Controllers\Admin;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;
use PDO;

class ConsultationController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private PDO $connection,
    ) {}

    #[Permission('consultations.view', 'View Consultations')]
    public function index(): void
    {
        $consultations = $this->consultationRepository->findAll();

        View::render('admin/consultations', [
            'consultations' => $consultations,
            'activePage' => 'admin-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.view', 'View Consultations')]
    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            redirect('/admin/consultations');
            return;
        }

        $stmt = $this->connection->prepare('SELECT * FROM consultation_images WHERE consultation_id = :cid');
        $stmt->execute([':cid' => $id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $experts = $this->userRepository->findExperts();
        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        View::render('admin/consultation-view', [
            'consultation' => $consultation,
            'images' => $images,
            'experts' => $experts,
            'farmer' => $farmer,
            'activePage' => 'admin-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.assign', 'Assign Expert')]
    public function assignExpert(): void
    {
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

        $expert = $this->userRepository->findById($expertId);
        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        if ($expert) {
            notify(
                $expert->getId(),
                'expert',
                'New consultation "' . $consultation->getTitle() . '" has been assigned to you.',
                'consultation_assigned',
                '/expert/consultations/hub'
            );
        }

        if ($farmer) {
            $expertName = $expert ? $expert->getUsername() : 'an expert';
            notify(
                $farmer->getId(),
                'farmer',
                'Your consultation "' . $consultation->getTitle() . '" has been assigned to ' . $expertName . '.',
                'consultation_assigned',
                '/consultations'
            );
        }

        $_SESSION['success'] = 'Expert assigned successfully.';
        redirect('/admin/consultations');
    }
}
