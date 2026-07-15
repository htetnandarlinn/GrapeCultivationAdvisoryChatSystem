<?php

namespace App\Presentation\Controllers\Payment;

use App\Application\ConsultationManagement\ProcessPayment\ProcessPaymentCommand;
use App\Application\ConsultationManagement\ProcessPayment\ProcessPaymentHandler;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;
use App\Shared\Exceptions\PaymentException;

class ConsultationPaymentController
{
    private string $uploadDir;

    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private ProcessPaymentHandler $paymentHandler,
        private NotificationService $notificationService,
    ) {
        $base = defined('BASE_URL') ? dirname($_SERVER['DOCUMENT_ROOT'] . BASE_URL) : $_SERVER['DOCUMENT_ROOT'];
        $this->uploadDir = $base . '/public/uploads/payments';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function showPayment(): void 
    {
        
        $id = (int) ($_GET['id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            redirect('/consultations');
            return;
        }

        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);
        if ($consultation->getFarmerId() !== $farmerId) {
            redirect('/consultations');
            return;
        }

        $status = $consultation->getStatus()->getValue();
        if (!in_array($status, ['awaiting_payment', 'payment_submitted', 'expired'], true)) {
            redirect('/consultations');
            return;
        }

        $expert = $consultation->getExpertId()
            ? $this->userRepository->findById($consultation->getExpertId())
            : null;

        View::render('payment/consultation-payment', [
            'consultation' => $consultation,
            'expert' => $expert,
        ], 'app');
    }

    public function processPayment(): void
    {
        
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $farmerId = (int) ($_SESSION['user']['id'] ?? 0);
        $idempotencyKey = trim($_POST['idempotency_key'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? '');
        

        if (!$id || !$idempotencyKey) {
            $_SESSION['error'] = 'Invalid payment request.';
            redirect('/consultations');
            return;
        }

        if (!in_array($paymentMethod, ['kpay', 'wavepay', 'paypal'], true)) {
            $_SESSION['error'] = 'Please select a valid payment method.';
            redirect('/payment/consultation?id=' . $id);
            return;
        }

        if (!isset($_FILES['transaction_image']) || $_FILES['transaction_image']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Please upload a transaction screenshot.';
            redirect('/payment/consultation?id=' . $id);
            return;
        }

        $file = $_FILES['transaction_image'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $_SESSION['error'] = 'Invalid image format. Accepted: jpg, png, gif, webp.';
            redirect('/payment/consultation?id=' . $id);
            return;
        }

        $filename = 'payment_' . $id . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
        $destPath = $this->uploadDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $_SESSION['error'] = 'Failed to upload receipt. Please try again.';
            redirect('/payment/consultation?id=' . $id);
            return;
        }

        $transactionImage = 'uploads/payments/' . $filename;

        try {
            $this->paymentHandler->handle(
                new ProcessPaymentCommand(
                    consultationId: $id,
                    farmerId: $farmerId,
                    idempotencyKey: $idempotencyKey,
                    paymentMethod: $paymentMethod,
                    transactionImage: $transactionImage,
                )
            );

            // Notify all admins that payment has been submitted for review
            $this->notificationService->notifyAllAdmins(
                "Payment submitted for consultation #{$id}. Please review the receipt.",
                'payment_submitted_admin',
                '/admin/payments'
            );

            // Notify farmer that receipt is under review
            $this->notificationService->notify(
                $farmerId,
                'farmer',
                "Payment receipt submitted for consultation #{$id}. Waiting for admin approval.",
                'payment_submitted',
                '/consultations'
            );

            $_SESSION['success'] = 'Payment receipt submitted! Waiting for admin approval.';
        } catch (PaymentException $e) {
            // Clean up uploaded file on failure
            if (file_exists($destPath)) {
                unlink($destPath);
            }
            $_SESSION['error'] = $e->getMessage();
        }

        redirect('/consultations');
    }
}
