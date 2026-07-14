<?php

namespace App\Presentation\Controllers\Payment;

use App\Application\ConsultationManagement\ProcessPayment\ProcessPaymentCommand;
use App\Application\ConsultationManagement\ProcessPayment\ProcessPaymentHandler;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;
use App\Shared\Exceptions\PaymentException;

class ConsultationPaymentController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private ProcessPaymentHandler $paymentHandler,
        private NotificationService $notificationService,
    ) {}

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
        if (!in_array($status, ['awaiting_payment', 'expired'], true)) {
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

        if (!$id || !$idempotencyKey) {
            $_SESSION['error'] = 'Invalid payment request.';
            redirect('/consultations');
            return;
        }

        try {
            $this->paymentHandler->handle(
                new ProcessPaymentCommand(
                    consultationId: $id,
                    farmerId: $farmerId,
                    idempotencyKey: $idempotencyKey,
                )
            );

            $consultation = $this->consultationRepository->findById($id);

            $farmer = $this->userRepository->findById($farmerId);
            if ($farmer && $consultation?->getExpertId()) {
                $expert = $this->userRepository->findById($consultation->getExpertId());
                if ($expert) {
                    $this->notificationService->notify(
                        $consultation->getExpertId(),
                        'expert',
                        "Payment received for consultation #{$id}. You can now start the consultation.",
                        'payment_received',
                        '/expert/consultations/hub'
                    );
                }

                $this->notificationService->notify(
                    $farmerId,
                    'farmer',
                    "Payment successful! Your consultation is now active for 30 days.",
                    'payment_success',
                    '/consultations'
                );
            }

            $_SESSION['success'] = 'Payment successful! Your consultation is now active.';
        } catch (PaymentException $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        redirect('/consultations');
    }
}
