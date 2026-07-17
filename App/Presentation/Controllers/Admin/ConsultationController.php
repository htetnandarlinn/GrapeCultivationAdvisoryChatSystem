<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\ConsultationManagement\Payment\PricingService;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationImageRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ExpertPayoutRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

class ConsultationController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private NotificationService $notificationService,
        private PaymentRepositoryInterface $paymentRepository,
        private PricingService $pricingService,
        private ConsultationImageRepositoryInterface $consultationImageRepository,
        private ExpertPayoutRepositoryInterface $expertPayoutRepository,
    ) {}

    private function notifySelf(string $message, string $type, ?string $link = null): void
    {
        $adminId = (int) ($_SESSION['user_id'] ?? 0);
        if ($adminId > 0) {
            $this->notificationService->notify($adminId, 'admin', $message, $type, $link);
        }
    }

    #[Permission('consultations.view', 'View Payments')]
    public function payments(): void
    {
        $allConsultations = $this->consultationRepository->findAll();

        $paymentMap = $this->paymentRepository->findByConsultationIds(
            array_map(static fn($c) => $c->getId(), $allConsultations)
        );

        $payoutMap = [];
        foreach ($this->expertPayoutRepository->findAll() as $payout) {
            $payoutMap[$payout->getConsultationId()] = $payout;
        }

        $payments = [];
        foreach ($allConsultations as $c) {
            $status = $c->getStatus()->getValue();
            if (in_array($status, ['awaiting_payment', 'payment_submitted', 'accepted', 'chat_started', 'completed', 'closed', 'expired', 'rejected'], true)) {
                $farmer = $this->userRepository->findById($c->getFarmerId());
                $expert = $c->getExpertId() ? $this->userRepository->findById($c->getExpertId()) : null;

                $image = $this->consultationImageRepository->findFirstImagePath($c->getId());

                $payment = $paymentMap[$c->getId()] ?? null;
                $amount = $payment ? $payment->getAmount() : 0.0;

                $payments[] = [
                    'id' => $c->getId(),
                    'title' => $c->getTitle(),
                    'status' => $status,
                    'farmer' => $farmer,
                    'expert' => $expert,
                    'farmer_name' => $farmer ? $farmer->getUsername() : 'Unknown',
                    'expert_name' => $expert ? $expert->getUsername() : null,
                    'amount' => $amount,
                    'paid_at' => $c->getPaidAt(),
                    'expires_at' => $c->getExpiresAt(),
                    'created_at' => $c->getCreatedAt(),
                    'image' => $image,
                    'payment_method' => $c->getPaymentMethod(),
                    'transaction_image' => $c->getTransactionImage(),
                    'farmer_id' => $c->getFarmerId(),
                    'verified_by' => $c->getVerifiedBy(),
                    'verified_at' => $c->getVerifiedAt(),
                    'refund_status' => $c->getRefundStatus(),
                    'refund_date' => $c->getRefundDate(),
                    'refund_amount' => $c->getRefundAmount(),
                    'admin_notes' => $c->getAdminNotes(),
                    'payout' => $payoutMap[$c->getId()] ?? null,
                ];
            }
        }

        $totalRevenue = 0;
        $paymentCount = 0;
        $pendingReviewCount = 0;
        $awaitingCount = 0;
        $expiredCount = 0;
        foreach ($payments as $p) {
            $s = $p['status'];
            if (in_array($s, ['accepted', 'chat_started', 'completed'], true)) {
                $paymentCount++;
                $totalRevenue += $p['amount'] ?? 0.0;
            } elseif ($s === 'payment_submitted') {
                $pendingReviewCount++;
            } elseif ($s === 'awaiting_payment') {
                $awaitingCount++;
            } elseif ($s === 'expired') {
                $expiredCount++;
                if ($p['paid_at']) $totalRevenue += $p['amount'] ?? 0.0;
            }
        }

        View::render('admin/payments', [
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
            'paymentCount' => $paymentCount,
            'pendingReviewCount' => $pendingReviewCount,
            'awaitingCount' => $awaitingCount,
            'expiredCount' => $expiredCount,
            'totalPayoutPending' => $this->expertPayoutRepository->sumPendingNet(),
            'totalPayoutReleased' => $this->expertPayoutRepository->sumReleasedNet(),
            'activePage' => 'admin-payments',
        ], 'dashboard');
    }

    #[Permission('consultations.view', 'View Consultations')]
    public function index(): void
    {
        $consultations = $this->consultationRepository->findAll();

        $images = [];
        foreach ($consultations as $c) {
            $images[$c->getId()] = $this->consultationImageRepository->findFirstImagePath($c->getId());
        }

        View::render('admin/consultations', [
            'consultations' => $consultations,
            'consultationImages' => $images,
            'consultationFee' => $this->pricingService->getConsultationFee(),
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

        $images = $this->consultationImageRepository->findByConsultationId($id);

        $experts = $this->userRepository->findExperts();
        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        View::render('admin/consultation-view', [
            'consultation' => $consultation,
            'images' => $images,
            'experts' => $experts,
            'farmer' => $farmer,
            'consultationFee' => $this->pricingService->getConsultationFee(),
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
            $this->notificationService->notify(
                $expert->getId(),
                'expert',
                'New consultation "' . $consultation->getTitle() . '" has been assigned to you. Please accept or decline.',
                'consultation_assigned',
                '/expert/consultations/hub'
            );
        }

        if ($farmer) {
            $expertName = $expert ? $expert->getUsername() : 'an expert';
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your consultation "' . $consultation->getTitle() . '" has been assigned to an expert. Waiting for expert acceptance.',
                'consultation_assigned',
                '/consultations'
            );
        }

        $_SESSION['success'] = 'Expert assigned successfully. Waiting for expert to accept.';
        $this->notifySelf('You assigned ' . ($expert ? $expert->getUsername() : 'an expert') . ' to consultation "' . $consultation->getTitle() . '".', 'admin_action', '/admin/consultations');
        redirect('/admin/consultations');
    }

    #[Permission('consultations.view', 'Approve Payment')]
    public function approvePayment(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/admin/payments');
            return;
        }

        $status = $consultation->getStatus()->getValue();
        if (!in_array($status, ['payment_submitted', 'awaiting_payment'], true)) {
            $_SESSION['error'] = 'Payment can only be approved for consultations with submitted payment.';
            redirect('/admin/payments');
            return;
        }

        $adminName = $_SESSION['user']['username'] ?? 'Admin';
        $consultation->markPaymentApproved($adminName);
        $this->consultationRepository->update($consultation);

        // Update the payments table
        $this->paymentRepository->markApproved($consultation->getId(), $adminName);

        // Calculate expert earnings and create a pending expert payout record
        $gross = $this->pricingService->getConsultationFee();
        $platformFee = $this->pricingService->calculatePlatformFee($gross);
        $net = $this->pricingService->calculateExpertPayout($gross);

        $existingPayout = $this->expertPayoutRepository->findByConsultationId($consultation->getId());
        if ($existingPayout === null) {
            $payout = new \App\Domain\ConsultationManagement\Entities\ExpertPayout(
                id: null,
                consultationId: $consultation->getId(),
                paymentId: null,
                expertId: (int) $consultation->getExpertId(),
                farmerId: $consultation->getFarmerId(),
                grossAmount: $gross,
                platformFee: $platformFee,
                netAmount: $net,
                status: \App\Domain\ConsultationManagement\ValueObjects\PayoutStatus::pending(),
            );
            $this->expertPayoutRepository->save($payout);
        }

        // Notify farmer
        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your payment for consultation #' . $id . ' has been approved! You can now chat with the expert.',
                'payment_approved',
                '/consultations'
            );
        }

        // Notify expert
        $expertId = $consultation->getExpertId();
        if ($expertId) {
            $expert = $this->userRepository->findById($expertId);
            if ($expert) {
                $this->notificationService->notify(
                    $expertId,
                    'expert',
                    'Payment confirmed for consultation #' . $id . '. You may begin the consultation.',
                    'payment_confirmed',
                    '/expert/consultations/hub'
                );
            }
        }

        $_SESSION['success'] = 'Payment approved successfully. Consultation is now active.';
        $this->notifySelf('You approved the payment for consultation #' . $id . ' ("' . $consultation->getTitle() . '").', 'admin_action', '/admin/payments');
        redirect('/admin/payments');
    }

    #[Permission('consultations.view', 'Release Expert Payout')]
    public function releasePayout(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/admin/payments');
            return;
        }

        $status = $consultation->getStatus()->getValue();
        if (!in_array($status, ['accepted', 'chat_started', 'completed'], true)) {
            $_SESSION['error'] = 'Payout can only be released for an active or completed consultation.';
            redirect('/admin/payments');
            return;
        }

        $adminName = $_SESSION['user']['username'] ?? 'Admin';

        $consultation->markCompleted();
        $this->consultationRepository->update($consultation);

        // Release the expert payout record (pending -> released)
        $payout = $this->expertPayoutRepository->findByConsultationId($id);
        if ($payout === null) {
            $gross = $this->pricingService->getConsultationFee();
            $payout = new \App\Domain\ConsultationManagement\Entities\ExpertPayout(
                id: null,
                consultationId: $id,
                paymentId: null,
                expertId: (int) $consultation->getExpertId(),
                farmerId: $consultation->getFarmerId(),
                grossAmount: $gross,
                platformFee: $this->pricingService->calculatePlatformFee($gross),
                netAmount: $this->pricingService->calculateExpertPayout($gross),
                status: \App\Domain\ConsultationManagement\ValueObjects\PayoutStatus::pending(),
            );
            $this->expertPayoutRepository->save($payout);
        }

        $payoutAmount = $payout->getNetAmount();

        if ($payout->isReleased()) {
            $_SESSION['success'] = 'Payout for consultation #' . $id . ' was already released ($' . number_format($payoutAmount, 2) . ').';
            redirect('/admin/payments');
            return;
        }

        $payout->markReleased($adminName);
        $this->expertPayoutRepository->save($payout);

        // Notify the expert that their payout has been released (farmer paid admin -> admin pays expert)
        $expertId = $consultation->getExpertId();
        if ($expertId) {
            $expert = $this->userRepository->findById($expertId);
            if ($expert) {
                $this->notificationService->notify(
                    $expertId,
                    'expert',
                    'Your payout of $' . number_format($payoutAmount, 2) . ' for consultation #' . $id . ' ("' . $consultation->getTitle() . '") has been released by admin.',
                    'expert_payout_released',
                    '/expert/payouts'
                );
            }
        }

        // Notify the farmer that the consultation is complete
        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            $expertName = $expert ? $expert->getUsername() : 'the expert';
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your consultation #' . $id . ' with ' . $expertName . ' is complete. Thank you for using our advisory service.',
                'consultation_completed',
                '/consultations'
            );
        }

        $_SESSION['success'] = 'Expert payout of $' . number_format($payoutAmount, 2) . ' released successfully.';
        $this->notifySelf('You released an expert payout of $' . number_format($payoutAmount, 2) . ' for consultation #' . $id . ' ("' . $consultation->getTitle() . '").', 'admin_action', '/admin/payments');
        redirect('/admin/payments');
    }

    #[Permission('consultations.view', 'Reject Payment')]
    public function rejectPayment(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/admin/payments');
            return;
        }

        $status = $consultation->getStatus()->getValue();
        if (!in_array($status, ['payment_submitted', 'awaiting_payment'], true)) {
            $_SESSION['error'] = 'Payment can only be rejected for pending/submitted consultations.';
            redirect('/admin/payments');
            return;
        }

        // Remove transaction image file
        $imagePath = $consultation->getTransactionImage();
        if ($imagePath) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Reset back to awaiting_payment
        $consultation->setTransactionImage(null);
        $consultation->setPaymentMethod(null);
        $consultation->markAwaitingPayment();
        $this->consultationRepository->update($consultation);

        // Update payment record
        $this->paymentRepository->markRejected($consultation->getId());

        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your payment receipt for consultation #' . $id . ' was rejected. Please upload a valid transaction screenshot.',
                'payment_rejected',
                '/payment/consultation?id=' . $id
            );
        }

        $_SESSION['success'] = 'Payment receipt rejected. Farmer has been notified to re-upload.';
        $this->notifySelf('You rejected the payment receipt for consultation #' . $id . ' ("' . $consultation->getTitle() . '").', 'admin_action', '/admin/payments');
        redirect('/admin/payments');
    }

    #[Permission('consultations.view', 'Refund Payment')]
    public function refundPayment(): void
    {
        $id = (int) ($_POST['consultation_id'] ?? 0);
        $consultation = $this->consultationRepository->findById($id);

        if (!$consultation) {
            $_SESSION['error'] = 'Consultation not found.';
            redirect('/admin/payments');
            return;
        }

        $status = $consultation->getStatus()->getValue();

        // Refund business logic:
        // - completed consultations cannot be refunded
        if ($status === 'completed') {
            $_SESSION['error'] = 'Refund not allowed. Consultation has been completed.';
            redirect('/admin/payments');
            return;
        }

        // Calculate refund amount based on rules
        $refundAmount = $this->pricingService->calculateRefundAmount($status);
        $refundNote = $this->pricingService->getRefundNote($status);

        // Remove transaction image file
        $imagePath = $consultation->getTransactionImage();
        if ($imagePath) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        // Apply refund
        $adminName = $_SESSION['user']['username'] ?? 'Admin';
        if ($refundAmount > 0) {
            $consultation->markRefunded($refundAmount, $refundNote);
        } else {
            $consultation->setTransactionImage(null);
            $consultation->setPaymentMethod(null);
            $consultation->markAwaitingPayment();
        }
        $this->consultationRepository->update($consultation);

        // Update payment record
        $this->paymentRepository->markRefunded($consultation->getId(), $refundAmount, $adminName);

        $farmer = $this->userRepository->findById($consultation->getFarmerId());
        if ($farmer) {
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your payment for consultation #' . $id . ' has been refunded ($' . number_format($refundAmount, 2) . '). ' . $refundNote,
                'payment_refunded',
                '/payment/consultation?id=' . $id
            );
        }

        $_SESSION['success'] = 'Payment refunded successfully ($' . number_format($refundAmount, 2) . ').';
        $this->notifySelf('You refunded $' . number_format($refundAmount, 2) . ' for consultation #' . $id . ' ("' . $consultation->getTitle() . '").', 'admin_action', '/admin/payments');
        redirect('/admin/payments');
    }
}
