<?php

namespace App\Presentation\Controllers\Dashboard;

use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Views\View;

class DashboardController
{
    public function __construct(
        private ?UserRepositoryInterface $userRepository = null,
        private ?ActivityRepositoryInterface $activityRepository = null,
        private ?ConsultationRepositoryInterface $consultationRepository = null,
        private ?ArticleRepositoryInterface $articleRepository = null,
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

        $data = ['activePage' => 'dashboard'];

        if ($this->userRepository) {
            $data['farmerCount'] = $this->userRepository->countFarmers();
            $data['expertCount'] = $this->userRepository->countExperts();
        }

        if ($this->activityRepository) {
            $data['activities'] = $this->activityRepository->getRecentActivities(8);
        }

        if ($this->consultationRepository && $role === 'admin') {
            $data['totalConsultations'] = $this->consultationRepository->countAll();
        }

        if ($this->articleRepository && $role === 'admin') {
            $data['totalArticles'] = $this->articleRepository->countAll();
        }

        if ($this->consultationRepository && $role === 'farmer') {
            $consultations = $this->consultationRepository->findByFarmer($userId);
            $data['farmerConsultations'] = $consultations;
            $data['totalConsultations'] = count($consultations);
            $data['pendingConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'pending'));
            $data['acceptedConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'accepted'));
            $data['rejectedConsultations'] = count(array_filter($consultations, fn($c) => $c->getStatus()->getValue() === 'rejected'));
        }

        View::render('admin/admin-dashboard', $data);
    }
}
