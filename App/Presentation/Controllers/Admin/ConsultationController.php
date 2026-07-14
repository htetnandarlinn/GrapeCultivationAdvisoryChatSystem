<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;
use PDO;

class ConsultationController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private NotificationService $notificationService,
        private PDO $connection,
    ) {}

    #[Permission('consultations.view', 'View Payments')]
    public function payments(): void
    {
        $allConsultations = $this->consultationRepository->findAll();

        $payments = [];
        foreach ($allConsultations as $c) {
            $status = $c->getStatus()->getValue();
            if (in_array($status, ['awaiting_payment', 'payment_submitted', 'accepted', 'chat_started', 'completed', 'closed', 'expired', 'rejected'], true)) {
                $farmer = $this->userRepository->findById($c->getFarmerId());
                $expert = $c->getExpertId() ? $this->userRepository->findById($c->getExpertId()) : null;

                $stmt = $this->connection->prepare('SELECT image_path FROM consultation_images WHERE consultation_id = :cid LIMIT 1');
                $stmt->execute([':cid' => $c->getId()]);
                $image = $stmt->fetch(PDO::FETCH_ASSOC);

                $payments[] = [
                    'id' => $c->getId(),
                    'title' => $c->getTitle(),
                    'status' => $status,
                    'farmer' => $farmer,
                    'expert' => $expert,
                    'farmer_name' => $farmer ? $farmer->getUsername() : 'Unknown',
                    'expert_name' => $expert ? $expert->getUsername() : null,
                    'paid_at' => $c->getPaidAt(),
                    'expires_at' => $c->getExpiresAt(),
                    'created_at' => $c->getCreatedAt(),
                    'image' => $image ? $image['image_path'] : null,
                    'payment_method' => $c->getPaymentMethod(),
                    'transaction_image' => $c->getTransactionImage(),
                    'farmer_id' => $c->getFarmerId(),
                    'verified_by' => $c->getVerifiedBy(),
                    'verified_at' => $c->getVerifiedAt(),
                    'refund_status' => $c->getRefundStatus(),
                    'refund_date' => $c->getRefundDate(),
                    'refund_amount' => $c->getRefundAmount(),
                    'admin_notes' => $c->getAdminNotes(),
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
                $totalRevenue += 29.99;
            } elseif ($s === 'payment_submitted') {
                $pendingReviewCount++;
            } elseif ($s === 'awaiting_payment') {
                $awaitingCount++;
            } elseif ($s === 'expired') {
                $expiredCount++;
                if ($p['paid_at']) $totalRevenue += 29.99;
            }
        }

        View::render('admin/payments', [
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
            'paymentCount' => $paymentCount,
            'pendingReviewCount' => $pendingReviewCount,
            'awaitingCount' => $awaitingCount,
            'expiredCount' => $expiredCount,
            'activePage' => 'admin-payments',
        ], 'dashboard');
    }

    #[Permission('consultations.view', 'View Consultations')]
    public function index(): void
    {
        $consultations = $this->consultationRepository->findAll();

        $images = [];
        foreach ($consultations as $c) {
            $stmt = $this->connection->prepare('SELECT image_path FROM consultation_images WHERE consultation_id = :cid LIMIT 1');
            $stmt->execute([':cid' => $c->getId()]);
            $img = $stmt->fetch(PDO::FETCH_ASSOC);
            $images[$c->getId()] = $img ? $img['image_path'] : null;
        }

        View::render('admin/consultations', [
            'consultations' => $consultations,
            'consultationImages' => $images,
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
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                payment_date = NOW(),
                start_date = CURDATE(),
                expiry_date = DATE_ADD(CURDATE(), INTERVAL 30 DAY),
                verified_by = :verified_by,
                verified_at = NOW(),
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => 'PAID',
            ':verified_by' => $adminName,
            ':consultation_id' => $consultation->getId(),
        ]);

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
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                transaction_image = NULL,
                payment_method = NULL,
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => 'REJECTED',
            ':consultation_id' => $consultation->getId(),
        ]);

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
        $refundAmount = 0.0;
        $refundNote = '';

        if ($status === 'rejected') {
            // Expert rejected assignment → full refund
            $refundAmount = 29.99;
            $refundNote = 'Full refund - expert rejected assignment';
        } elseif ($status === 'assigned' || $status === 'expert_accepted') {
            // Farmer cancels before expert starts → 80% refund
            $refundAmount = 23.99; // 80% of 29.99
            $refundNote = '80% refund - farmer cancelled before expert started';
        } elseif (in_array($status, ['accepted', 'chat_started'])) {
            // Admin cancelled → full refund
            $refundAmount = 29.99;
            $refundNote = 'Full refund - admin cancelled';
        } elseif (in_array($status, ['payment_submitted', 'awaiting_payment'])) {
            // Payment not yet approved, just reset
            $refundAmount = 0.0;
            $refundNote = 'Payment receipt rejected, no charge made';
        }

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
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                refund_status = :refund_status,
                refund_date = NOW(),
                refund_amount = :refund_amount,
                verified_by = :verified_by,
                verified_at = NOW(),
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => 'REFUNDED',
            ':refund_status' => 'refunded',
            ':refund_amount' => $refundAmount,
            ':verified_by' => $adminName,
            ':consultation_id' => $consultation->getId(),
        ]);

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
        redirect('/admin/payments');
    }
}
