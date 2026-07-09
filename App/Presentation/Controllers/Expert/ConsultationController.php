<?php

namespace App\Presentation\Controllers\Expert;

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

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function index(): void
    {
        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        $consultations = $this->consultationRepository->findByExpert($expertId);

        View::render('expert/consultations', [
            'consultations' => $consultations,
            'activePage' => 'expert-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function view(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            redirect('/access-denied');
            return;
        }

        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        $stmt = $this->connection->prepare('SELECT * FROM consultation_images WHERE consultation_id = :cid');
        $stmt->execute([':cid' => $id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('expert/consultation-view', [
            'consultation' => $consultation,
            'farmer' => $farmer,
            'images' => $images,
            'activePage' => 'expert-consultations',
        ], 'dashboard');
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function accept(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            redirect('/access-denied');
            return;
        }

        $consultation->accept();
        $this->consultationRepository->update($consultation);

        $_SESSION['success'] = 'Consultation accepted.';
        redirect('/expert/consultations/view?id=' . $id);
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function reject(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $note = trim($_POST['rejection_note'] ?? '');

        if (!$note) {
            $_SESSION['error'] = 'Rejection note is required.';
            redirect('/expert/consultations/view?id=' . $id);
            return;
        }

        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            redirect('/access-denied');
            return;
        }

        $consultation->reject($note);
        $this->consultationRepository->update($consultation);

        $_SESSION['success'] = 'Consultation rejected.';
        redirect('/expert/consultations');
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function chat(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            redirect('/access-denied');
            return;
        }

        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        $stmt = $this->connection->prepare('SELECT * FROM consultation_images WHERE consultation_id = :cid');
        $stmt->execute([':cid' => $id]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('expert/chat', [
            'consultation' => $consultation,
            'farmer' => $farmer,
            'images' => $images,
        ], '');
    }
}
