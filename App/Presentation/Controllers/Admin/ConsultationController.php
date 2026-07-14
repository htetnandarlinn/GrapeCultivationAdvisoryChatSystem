<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\NotificationManagement\NotificationService;
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
            if (in_array($status, ['awaiting_payment', 'accepted', 'expired'], true)) {
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
                ];
            }
        }

        // Stats
        $totalRevenue = 0;
        $paymentCount = 0;
        $awaitingCount = 0;
        $expiredCount = 0;
        foreach ($payments as $p) {
            if ($p['status'] === 'accepted') {
                $paymentCount++;
                $totalRevenue += 29.99;
            } elseif ($p['status'] === 'awaiting_payment') {
                $awaitingCount++;
            } elseif ($p['status'] === 'expired') {
                $expiredCount++;
                if ($p['paid_at']) $totalRevenue += 29.99;
            }
        }

        View::render('admin/payments', [
            'payments' => $payments,
            'totalRevenue' => $totalRevenue,
            'paymentCount' => $paymentCount,
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
        $consultation->markAwaitingPayment();
        $this->consultationRepository->update($consultation);

        $expert = $this->userRepository->findById($expertId);
        $farmer = $this->userRepository->findById($consultation->getFarmerId());

        if ($expert) {
            $this->notificationService->notify(
                $expert->getId(),
                'expert',
                'New consultation "' . $consultation->getTitle() . '" has been assigned to you.',
                'consultation_assigned',
                '/expert/consultations/hub'
            );
        }

        if ($farmer) {
            $expertName = $expert ? $expert->getUsername() : 'an expert';
            $this->notificationService->notify(
                $farmer->getId(),
                'farmer',
                'Your consultation "' . $consultation->getTitle() . '" requires payment to activate.',
                'consultation_awaiting_payment',
                '/payment/consultation?id=' . $consultation->getId()
            );
        }

        $_SESSION['success'] = 'Expert assigned successfully. Payment is now required from the farmer.';
        redirect('/admin/consultations');
    }
}
