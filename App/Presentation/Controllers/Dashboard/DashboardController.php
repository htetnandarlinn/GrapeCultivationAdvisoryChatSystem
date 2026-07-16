<?php

namespace App\Presentation\Controllers\Dashboard;

use App\Application\NotificationManagement\NotificationService;
use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Domain\NotificationManagement\Repositories\NotificationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;

class DashboardController
{
    public function __construct(
        private ?UserRepositoryInterface $userRepository = null,
        private ?ActivityRepositoryInterface $activityRepository = null,
        private ?ConsultationRepositoryInterface $consultationRepository = null,
        private ?ArticleRepositoryInterface $articleRepository = null,
        private ?NotificationRepositoryInterface $notificationRepository = null,
        private ?NotificationService $notificationService = null,
        private ?PaymentRepositoryInterface $paymentRepository = null,
    ) {}

    public function home(): void
    {
        $profiles = [];
        if ($this->userRepository) {
            $farmers = $this->userRepository->findFarmers();
            $experts = $this->userRepository->findExperts();
            shuffle($farmers);
            shuffle($experts);
            $profiles = array_merge(
                array_slice($farmers, 0, 2),
                array_slice($experts, 0, 1)
            );
            shuffle($profiles);
        }
        View::render('home', ['profiles' => $profiles], 'app');
    }

    public function about(): void
    {
        View::render('about', [], 'app');
    }

    public function contact(): void
    {
        View::render('contact', [], 'app');
    }

    public function handleContact(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];
        if ($name === '') $errors[] = 'Name is required.';
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'A valid email is required.';
        if ($subject === '') $errors[] = 'Subject is required.';
        if ($message === '') $errors[] = 'Message is required.';

        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
            return;
        }

        if ($this->notificationService) {
            $this->notificationService->notifyAllAdmins(
                "New contact message from $name ($email): $subject",
                'system',
                null
            );
        }

        echo json_encode(['success' => true]);
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        } 

        $role = $_SESSION['user_role'] ?? '';
        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if (!$role || !$userId) {
            redirect('/login');
            return;
        }
        $userdata  = $this->userRepository->findById($userId);

        if ($userdata === null) {
            redirect('/login');
            return;
        }

        if ($role === 'farmer') {
            redirect('/');
            return;
        }

        if ($role === 'expert') {
            redirect('/expert/consultations/hub');
            return;
        }
        $data = ['activePage' => 'dashboard'];

        if ($this->userRepository) {
            $data['farmerCount'] = $this->userRepository->countFarmers();
            $data['expertCount'] = $this->userRepository->countExperts();
        }

        if ($this->activityRepository) {
            $data['activities'] = $this->activityRepository->getRecentActivities(5);
        }

        if ($role === 'admin') {
            if ($this->consultationRepository) {
                $data['totalConsultations'] = $this->consultationRepository->countAll();

                $allConsultations = $this->consultationRepository->findAll();
                $data['adminConsultationList'] = array_slice($allConsultations, 0, 5);

                $pending = 0;
                $assigned = 0;
                $expertAccepted = 0;
                $accepted = 0;
                $chatStarted = 0;
                $completed = 0;
                $rejected = 0;
                foreach ($allConsultations as $c) {
                    match ($c->getStatus()->getValue()) {
                        'pending' => $pending++,
                        'assigned' => $assigned++,
                        'expert_accepted' => $expertAccepted++,
                        'accepted' => $accepted++,
                        'chat_started' => $chatStarted++,
                        'completed' => $completed++,
                        'rejected' => $rejected++,
                        default => null,
                    };
                }
                $data['adminPendingConsultations'] = $pending;
                $data['adminAssignedConsultations'] = $assigned + $expertAccepted;
                $data['adminAcceptedConsultations'] = $accepted + $chatStarted + $completed;
                $data['adminRejectedConsultations'] = $rejected;

                // Payment metrics
                $awaitingPayment = 0;
                $pendingReview = 0;
                $expiredCount = 0;
                $totalRevenue = 0;
                $refundedCount = 0;

                $paymentAmounts = [];
                if ($this->paymentRepository) {
                    $paymentAmounts = $this->paymentRepository->findByConsultationIds(
                        array_map(static fn($c) => $c->getId(), $allConsultations)
                    );
                }

                foreach ($allConsultations as $c) {
                    $s = $c->getStatus()->getValue();
                    if ($s === 'awaiting_payment') $awaitingPayment++;
                    if ($s === 'payment_submitted') $pendingReview++;
                    if ($s === 'expired') $expiredCount++;
                    if ($c->getRefundStatus() === 'refunded') $refundedCount++;
                    $payment = $paymentAmounts[$c->getId()] ?? null;
                    if ($payment && $payment->getStatus()->isPaid()) {
                        $totalRevenue += $payment->getAmount();
                    }
                }
                $data['adminAwaitingPayment'] = $awaitingPayment;
                $data['adminPendingReview'] = $pendingReview;
                $data['adminExpiredConsultations'] = $expiredCount;
                $data['adminTotalRevenue'] = $totalRevenue;
                $data['adminRefundedPayments'] = $refundedCount;

                // Top expert by income
                $expertIncome = [];
                foreach ($allConsultations as $c) {
                    $eid = $c->getExpertId();
                    $payment = $paymentAmounts[$c->getId()] ?? null;
                    if ($eid && $payment && $payment->getStatus()->isPaid()) {
                        $expertIncome[$eid] = ($expertIncome[$eid] ?? 0) + $payment->getAmount();
                    }
                }
                arsort($expertIncome);
                $data['adminTopExpertId'] = array_key_first($expertIncome);
                $data['adminTopExpertIncome'] = reset($expertIncome);
            }
            if ($this->articleRepository) {
                $data['totalArticles'] = $this->articleRepository->countAll();

                $allArticles = $this->articleRepository->findAll();
                $pendingArticles = array_filter($allArticles, fn($a) => $a->getStatus()->getValue() === 'pending');
                $data['adminPendingArticles'] = array_slice($pendingArticles, 0, 5);
            }
            if ($this->userRepository) {
                $farmers = $this->userRepository->findFarmers();
                $experts = $this->userRepository->findExperts();
                $data['adminRecentFarmers'] = array_slice($farmers, 0, 3);
                $data['adminRecentExperts'] = array_slice($experts, 0, 3);
            }
            if ($this->notificationRepository) {
                $data['adminUnreadNotifications'] = $this->notificationRepository->countUnread($userId);
            }
        }

        if ($this->consultationRepository && $role === 'farmer') {
            $consultations = $this->consultationRepository->findByFarmer($userId);
            $data['farmerConsultations'] = $consultations;
            $data['totalConsultations'] = count($consultations);
            $data['pendingConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'pending'));
            $data['acceptedConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'accepted'));
            $data['rejectedConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'rejected'));
        }

        if ($role === 'expert') {
            if ($this->consultationRepository) {
                $data['expertConsultations'] = $this->consultationRepository->countByExpert($userId);
                $data['expertConsultingFarmers'] = $this->consultationRepository->countDistinctFarmersByExpert($userId);

                $consultations = $this->consultationRepository->findByExpert($userId);
                $data['expertConsultationList'] = array_slice($consultations, 0, 5);

                $pending = 0;
                $assigned = 0;
                $accepted = 0;
                $rejected = 0;
                foreach ($consultations as $c) {
                    match ($c->getStatus()->getValue()) {
                        'pending' => $pending++,
                        'assigned' => $assigned++,
                        'accepted' => $accepted++,
                        'rejected' => $rejected++,
                        default => null,
                    };
                }
                $data['expertPendingConsultations'] = $pending;
                $data['expertAssignedConsultations'] = $assigned;
                $data['expertAcceptedConsultations'] = $accepted;
                $data['expertRejectedConsultations'] = $rejected;
            }
            if ($this->articleRepository) {
                $data['expertArticles'] = $this->articleRepository->countByAuthor($userId);
                $data['expertArticleImages'] = $this->articleRepository->countImagesByAuthor($userId);

                $articles = $this->articleRepository->findAll($userId);
                $data['expertArticleList'] = array_slice($articles, 0, 5);
            }
            if ($this->notificationRepository) {
                $data['expertUnreadNotifications'] = $this->notificationRepository->countUnread($userId);
            }
        }

        View::render('admin/admin-dashboard', $data);
    }
}
