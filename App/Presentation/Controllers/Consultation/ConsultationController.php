<?php

namespace App\Presentation\Controllers\Consultation;

use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationCommand;
use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationHandler;
use App\Application\ConsultationManagement\Payment\PricingService;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationImageRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;

class ConsultationController
{
    public function __construct(
        private CreateConsultationHandler $createHandler,
        private ConsultationRepositoryInterface $consultationRepository,
        private MessageRepositoryInterface $messageRepository,
        private UserRepositoryInterface $userRepository,
        private NotificationService $notificationService,
        private PaymentRepositoryInterface $paymentRepository,
        private PricingService $pricingService,
        private ConsultationImageRepositoryInterface $consultationImageRepository,
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

        // Block duplicate if an active consultation with the same title exists
        $active = $this->consultationRepository->findActiveByFarmerAndTitle($farmerId, $title);
        if (!empty($active)) {
            $_SESSION['error'] = 'You already have an active consultation with the title "' . htmlspecialchars($title) . '". Please complete or close it first.';
            redirect('/consultation/create');
            return;
        }

        $command = new CreateConsultationCommand(
            farmerId: $farmerId,
            title: $title,
            description: $description,
            consultationFee: $this->pricingService->getConsultationFee(),
        );

        $consultation = $this->createHandler->handle($command);
        $consultationId = $consultation->getId();

        $farmerName = $_SESSION['user']['username'] ?? 'A farmer';
        $this->notificationService->notifyAllAdmins(
            "$farmerName submitted a new consultation: " . $title,
            'consultation_created',
            '/notifications'
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
                $this->consultationImageRepository->create($consultationId, 'uploads/consultations/' . $filename);
            }
        }

        $_SESSION['success'] = 'Consultation submitted successfully. An expert will review it soon.';
        redirect('/consultations');
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

        // Block duplicate if an active consultation with the same title exists
        $active = $this->consultationRepository->findActiveByFarmerAndTitle($farmerId, $title);
        if (!empty($active)) {
            http_response_code(409);
            echo json_encode(['success' => false, 'error' => 'You already have an active consultation with the title "' . htmlspecialchars($title) . '". Please complete or close it first.']);
            return;
        }

        $command = new CreateConsultationCommand(
            farmerId: $farmerId,
            title: $title,
            description: $description,
            consultationFee: $this->pricingService->getConsultationFee(),
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
                $this->consultationImageRepository->create($consultationId, 'uploads/consultations/' . $filename);
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

        $images = $this->consultationImageRepository->findByConsultationIds(
            array_map(fn($c) => $c->getId(), $consultations)
        );

        View::render('consultation/my-consultations', [
            'consultations' => $consultations,
            'images' => $images,
            'consultationFee' => $this->pricingService->getConsultationFee(),
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
        $paymentRecords = $this->paymentRepository->findByConsultationIds(
            array_map(fn($c) => $c->getId(), $consultations)
        );

        View::render('consultation/payment-history', [
            'consultations' => $consultations,
            'paymentRecords' => $paymentRecords,
        ], 'app');
    }

    public function getStatusJson(): void
    {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $idsParam = trim($_GET['ids'] ?? '');
        if ($idsParam === '') {
            echo json_encode([]);
            return;
        }

        $ids = array_map('intval', array_filter(explode(',', $idsParam), fn($v) => is_numeric(trim($v))));

        if (empty($ids)) {
            echo json_encode([]);
            return;
        }

        $result = [];
        foreach ($ids as $id) {
            $consultation = $this->consultationRepository->findById($id);
            if ($consultation) {
                $result[$id] = $consultation->getStatus()->getValue();
            }
        }

        echo json_encode($result);
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

        $images = $this->consultationImageRepository->findByConsultationIds(
            array_map(fn($c) => $c->getId(), $consultations)
        );
        $lastMessages = [];
        $expertNames = [];
        $expertAvatars = [];
        if (!empty($consultations)) {
            $ids = array_map(fn($c) => $c->getId(), $consultations);

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
            'consultationFee' => $this->pricingService->getConsultationFee(),
        ], 'app');
    }
}
