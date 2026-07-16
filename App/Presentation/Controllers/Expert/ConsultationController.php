<?php

namespace App\Presentation\Controllers\Expert;

use App\Application\ConsultationManagement\Payment\PricingService;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Entities\Payment;
use App\Domain\ConsultationManagement\Repositories\ConsultationImageRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\PaymentStatus;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

class ConsultationController
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private UserRepositoryInterface $userRepository,
        private MessageRepositoryInterface $messageRepository,
        private NotificationService $notificationService,
        private PaymentRepositoryInterface $paymentRepository,
        private PricingService $pricingService,
        private ConsultationImageRepositoryInterface $consultationImageRepository,
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

        $images = $this->consultationImageRepository->findByConsultationId($id);

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
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        try {
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

            if ($consultation->getStatus()->getValue() !== 'assigned') {
                if ($isAjax) { header('Content-Type: application/json'); echo json_encode(['success' => false, 'error' => 'Consultation is not in assigned status.']); return; }
                $_SESSION['error'] = 'Consultation is not in assigned status.';
                redirect('/expert/consultations');
                return;
            }

            // Mark as expert accepted, then auto-create payment record and set awaiting payment
            $consultation->markExpertAccepted();
            $this->consultationRepository->update($consultation);

            // Auto-create payment record and move to awaiting_payment
            $idempotencyKey = 'sys_' . bin2hex(random_bytes(16));
            $consultation->markAwaitingPayment();
            $this->consultationRepository->update($consultation);

            // Insert payment record
            $this->paymentRepository->create(new Payment(
                id: null,
                consultationId: $consultation->getId(),
                farmerId: $consultation->getFarmerId(),
                amount: $this->pricingService->getConsultationFee(),
                status: PaymentStatus::pending(),
                transactionReference: $idempotencyKey,
                paymentMethod: null,
            ));

            // Notify farmer that payment is now needed
            $farmer = $this->userRepository->findById($consultation->getFarmerId());
            if ($farmer) {
                $this->notificationService->notify(
                    $farmer->getId(),
                    'farmer',
                    'Expert has accepted your consultation "' . $consultation->getTitle() . '". Please complete payment of $' . number_format($this->pricingService->getConsultationFee(), 2) . ' to start.',
                    'consultation_awaiting_payment',
                    '/payment/consultation?id=' . $consultation->getId()
                );
            }

            // Notify admins (delivered live via the topbar poll)
            $this->notificationService->notifyAllAdmins(
                'Expert has accepted consultation "' . $consultation->getTitle() . '". Payment requested from the farmer.',
                'consultation_expert_accepted',
                '/admin/consultations/view?id=' . $consultation->getId()
            );

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true]);
                return;
            }

            $_SESSION['success'] = 'Consultation accepted. Payment is now requested from the farmer.';
            redirect('/expert/consultations/view?id=' . $id);
        } catch (\Throwable $e) {
            error_log('Accept consultation failed: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'error' => 'Server error: ' . $e->getMessage()]);
                return;
            }
            $_SESSION['error'] = 'Failed to accept consultation: ' . $e->getMessage();
            redirect('/expert/consultations/hub');
        }
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
            $this->notificationService->notify(
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
    public function farmers(): void
    {
        $expertId = (int) ($_SESSION['user']['id'] ?? 0);
        $consultations = $this->consultationRepository->findByExpert($expertId);

        $farmerIds = [];
        foreach ($consultations as $c) {
            $fid = $c->getFarmerId();
            if (!in_array($fid, $farmerIds)) {
                $farmerIds[] = $fid;
            }
        }

        $farmers = [];
        foreach ($farmerIds as $fid) {
            $user = $this->userRepository->findById($fid);
            if ($user) {
                $farmers[] = $user;
            }
        }

        View::render('expert/farmers', [
            'activePage' => 'expert-consultations',
            'farmers' => $farmers,
            'consultations' => $consultations,
        ]);
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

            $images = $this->consultationImageRepository->findByConsultationIds($ids);

            $lastMessages = $this->messageRepository->findLastMessageByConsultationIds($ids);

            $farmerIds = array_unique(array_map(fn($c) => $c->getFarmerId(), $consultations));
            if (!empty($farmerIds)) {
                $farmers = $this->userRepository->findByIds(array_values($farmerIds));
                foreach ($farmers as $userId => $farmer) {
                    $farmerNames[$userId] = $farmer->getUsername();
                    $farmerAvatars[$userId] = $farmer->getProfileImage();
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
