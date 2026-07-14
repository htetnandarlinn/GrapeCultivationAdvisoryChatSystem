<?php

namespace App\Presentation\Controllers\Consultation;

use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationCommand;
use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationHandler;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;
use PDO;

class ConsultationController
{
    public function __construct(
        private CreateConsultationHandler $createHandler,
        private ConsultationRepositoryInterface $consultationRepository,
        private MessageRepositoryInterface $messageRepository,
        private UserRepositoryInterface $userRepository,
        private NotificationService $notificationService,
        private PDO $connection,
    ) {}

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        View::render('consultation/create', [], 'app');
    }

    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$title || !$description) {
            $_SESSION['error'] = 'Title and description are required.';
            redirect('/consultation/create');
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);

        $command = new CreateConsultationCommand(
            farmerId: $farmerId,
            title: $title,
            description: $description,
        );

        $consultation = $this->createHandler->handle($command);
        $consultationId = $consultation->getId();

        $farmerName = $_SESSION['user']['username'] ?? 'A farmer';
        $this->notificationService->notifyAllAdmins(
            "$farmerName submitted a new consultation: " . $title,
            'consultation_created',
            '/admin/consultations/view?id=' . $consultationId
        );
        $this->notificationService->notifyAllByRole(
            'farmer',
            "$farmerName submitted a new consultation: " . $title,
            'consultation_created',
            '/consultations'
        );

        if (!empty($_FILES['images']['name'][0])) {
            $uploadDir = __DIR__ . '/../../../../public/uploads/consultations/';
            foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) continue;
                $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                $filename = $consultationId . '_' . uniqid() . '.' . $ext;
                move_uploaded_file($tmpName, $uploadDir . $filename);
                $stmt = $this->connection->prepare('INSERT INTO consultation_images (consultation_id, image_path, created_at) VALUES (:cid, :path, NOW())');
                $stmt->execute([':cid' => $consultationId, ':path' => 'uploads/consultations/' . $filename]);
            }
        }

        $_SESSION['success'] = 'Consultation submitted successfully. An expert will review it soon.';
        redirect('/');
    }

    public function storeAjax(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            http_response_code(401);
            echo json_encode(['success' => false, 'error' => 'Unauthorized']);
            return;
        }

        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (!$title || !$description) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Title and description are required.']);
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);

        $command = new CreateConsultationCommand(
            farmerId: $farmerId,
            title: $title,
            description: $description,
        );

        try {
            $consultation = $this->createHandler->handle($command);
            $consultationId = $consultation->getId();

            $farmerName = $_SESSION['user']['username'] ?? 'A farmer';
            $this->notificationService->notifyAllAdmins(
                "$farmerName submitted a new consultation: " . $title,
                'consultation_created',
                '/admin/consultations/view?id=' . $consultationId
            );
            $this->notificationService->notifyAllByRole(
                'farmer',
                "$farmerName submitted a new consultation: " . $title,
                'consultation_created',
                '/consultations'
            );

            if (!empty($_FILES['images']['name'][0])) {
                $uploadDir = __DIR__ . '/../../../../public/uploads/consultations/';
                foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
                    if ($_FILES['images']['error'][$key] !== UPLOAD_ERR_OK) continue;
                    $ext = pathinfo($_FILES['images']['name'][$key], PATHINFO_EXTENSION);
                    $filename = $consultationId . '_' . uniqid() . '.' . $ext;
                    move_uploaded_file($tmpName, $uploadDir . $filename);
                    $stmt = $this->connection->prepare('INSERT INTO consultation_images (consultation_id, image_path, created_at) VALUES (:cid, :path, NOW())');
                    $stmt->execute([':cid' => $consultationId, ':path' => 'uploads/consultations/' . $filename]);
                }
            }

            echo json_encode(['success' => true]);
        } catch (\Throwable $e) {
            http_response_code(500);
            error_log('Consultation create failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            echo json_encode(['success' => false, 'error' => 'Failed to create consultation.']);
        }
    }

    public function myConsultations(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);
        $consultations = $this->consultationRepository->findByFarmer($farmerId);

        $images = [];
        if (!empty($consultations)) {
            $ids = array_map(fn($c) => $c->getId(), $consultations);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->connection->prepare("SELECT * FROM consultation_images WHERE consultation_id IN ($placeholders)");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $images[$row['consultation_id']][] = $row;
            }
        }

        View::render('consultation/my-consultations', [
            'consultations' => $consultations,
            'images' => $images,
        ], 'dashboard');
    }

    public function paymentHistory(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/login');
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);
        $allConsultations = $this->consultationRepository->findByFarmer($farmerId);

        $consultations = array_filter($allConsultations, fn($c) => in_array(
            $c->getStatus()->getValue(),
            ['awaiting_payment', 'payment_submitted', 'accepted', 'chat_started', 'completed', 'closed', 'expired'],
            true
        ));

        // Get payment data for each consultation
        $paymentRecords = [];
        if (!empty($consultations)) {
            $ids = array_map(fn($c) => $c->getId(), $consultations);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $this->connection->prepare("SELECT * FROM payments WHERE consultation_id IN ($placeholders)");
            $stmt->execute(array_values($ids));
            foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $row) {
                $paymentRecords[(int) $row['consultation_id']] = $row;
            }
        }

        View::render('consultation/payment-history', [
            'consultations' => $consultations,
            'paymentRecords' => $paymentRecords,
        ], 'app');
    }

    public function chat(): void
    {
        redirect('/consultations');
    }

    public function frontendHistory(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/login');
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);
        $consultations = $this->consultationRepository->findByFarmer($farmerId);

        $images = [];
        $lastMessages = [];
        $expertNames = [];
        $expertAvatars = [];
        if (!empty($consultations)) {
            $ids = array_map(fn($c) => $c->getId(), $consultations);
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Fetch images
            $stmt = $this->connection->prepare("SELECT * FROM consultation_images WHERE consultation_id IN ($placeholders)");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                $images[$row['consultation_id']][] = $row;
            }

            $lastMessages = $this->messageRepository->findLastMessageByConsultationIds($ids);

            $expertIds = array_unique(array_filter(array_map(fn($c) => $c->getExpertId(), $consultations)));
            if (!empty($expertIds)) {
                $experts = $this->userRepository->findByIds(array_values($expertIds));
                foreach ($experts as $userId => $expert) {
                    $expertNames[$userId] = $expert->getUsername();
                    $expertAvatars[$userId] = $expert->getProfileImage();
                }
            }
        }

        View::render('consultation/frontend-history', [
            'consultations' => $consultations,
            'images' => $images,
            'lastMessages' => $lastMessages,
            'expertNames' => $expertNames,
            'expertAvatars' => $expertAvatars,
        ], 'app');
    }
}
