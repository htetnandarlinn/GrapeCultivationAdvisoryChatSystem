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
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$consultation) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Consultation not found.']); return; }
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Access denied.']); return; }
            redirect('/access-denied');
            return;
        }

        $consultation->accept();
        $this->consultationRepository->update($consultation);

        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            notify(
                $farmer->getId(),
                'farmer',
                'Your consultation "' . $consultation->getTitle() . '" has been accepted by an expert.',
                'consultation_accepted',
                '/consultations'
            );
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        }

        $_SESSION['success'] = 'Consultation accepted.';
        redirect('/expert/consultations/view?id=' . $id);
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function reject(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $note = trim($_POST['rejection_note'] ?? '');
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!$note) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Rejection note is required.']); return; }
            $_SESSION['error'] = 'Rejection note is required.';
            redirect('/expert/consultations/view?id=' . $id);
            return;
        }

        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Consultation not found.']); return; }
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/expert/consultations');
            return;
        }

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getExpertId() !== $expertId) {
            if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Access denied.']); return; }
            redirect('/access-denied');
            return;
        }

        $consultation->reject($note);
        $this->consultationRepository->update($consultation);

        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            notify(
                $farmer->getId(),
                'farmer',
                'Your consultation "' . $consultation->getTitle() . '" was rejected. Reason: ' . $note,
                'consultation_rejected',
                '/consultations'
            );
        }

        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            return;
        }

        $_SESSION['success'] = 'Consultation rejected.';
        redirect('/expert/consultations');
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function chat(): void
    {
        $id = (int) ($_GET['id'] ?? 0);
        redirect('/expert/consultations/hub' . ($id ? '?id=' . $id : ''));
    }

    #[Permission('consultations.answer', 'Answer Consultations')]
    public function hub(): void
    {
        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        $consultations = $this->consultationRepository->findByExpert($expertId);

        $images = [];
        $lastMessages = [];
        $farmerNames = [];
        $farmerAvatars = [];

        if (!empty($consultations)) {
            $ids = array_map(fn($c) => $c->getId(), $consultations);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            $stmt = $this->connection->prepare("SELECT * FROM consultation_images WHERE consultation_id IN ($placeholders)");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $images[$row['consultation_id']][] = $row;
            }

            $stmt = $this->connection->prepare("
                SELECT m.*, u.username as sender_name
                FROM messages m
                LEFT JOIN users u ON m.sender_id = u.user_id
                WHERE m.message_id IN (
                    SELECT MAX(m2.message_id) FROM messages m2 WHERE m2.consultation_id IN ($placeholders) GROUP BY m2.consultation_id
                )
            ");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $lastMessages[$row['consultation_id']] = $row;
            }

            $farmerIds = array_unique(array_map(fn($c) => $c->getFarmerId(), $consultations));
            if (!empty($farmerIds)) {
                $ePlaceholders = implode(',', array_fill(0, count($farmerIds), '?'));
                $stmt = $this->connection->prepare("SELECT user_id, username, profile_image FROM users WHERE user_id IN ($ePlaceholders)");
                $stmt->execute(array_values($farmerIds));
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    $farmerNames[(int)$row['user_id']] = $row['username'];
                    $farmerAvatars[(int)$row['user_id']] = $row['profile_image'];
                }
            }
        }

        View::render('expert/consultation-hub', [
            'consultations' => $consultations,
            'images' => $images,
            'lastMessages' => $lastMessages,
            'farmerNames' => $farmerNames,
            'farmerAvatars' => $farmerAvatars,
        ], 'dashboard');
    }
}
