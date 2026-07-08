<?php

namespace App\Presentation\Controllers\Dashboard;

use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;

class DashboardController
{
    use AuthorizesPermissions;

    public function __construct(
        private ?UserRepositoryInterface $userRepository = null,
        private ?ActivityRepositoryInterface $activityRepository = null,
        private ?QuestionRepositoryInterface $questionRepository = null,
    ) {}

    public function home(): void
    {
        View::render('home');
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

        if ($this->questionRepository) {
            $questions = match ($role) {
                'farmer' => $this->questionRepository->findByFarmer($userId),
                'expert' => $this->questionRepository->findPending(),
                default => $this->questionRepository->findAll(),
            };

            $data['questions'] = $questions;
            $data['totalQuestions'] = count($questions);
            $data['pendingQuestions'] = count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Pending'));
            $data['answeredQuestions'] = count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Answered'));
            $data['imageCount'] = count(array_filter($questions, fn($q) => !empty($q['image'])));
        }

        View::render('admin/admin-dashboard', $data);
    }

    #[Permission('questions.ask', 'Ask Question')]
    public function askQuestion(): void
    {
        $this->authorize('questions.ask');
        $categories = $this->questionRepository->findCategories();

        View::render('farmer/ask-question', [
            'activePage' => 'ask',
            'categories' => $categories
        ]);
    }

    #[Permission('questions.ask', 'Ask Question')]
    public function submitQuestion(): void
    {
        $this->authorize('questions.ask');
        View::render('farmer/question-submitted', [
            'activePage' => 'submit'
        ]);
    }

    #[Permission('questions.view', 'View My Questions')]
    public function totalQuestions(): void
    {
        $this->authorize('questions.view');
        $farmerId = (int) $_SESSION['user']['id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        View::render('admin/admin-dashboard', [
            'activePage'        => 'dashboard',
            'questions'         => $questions,
            'totalQuestions'    => count($questions),
            'pendingQuestions'  => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Pending')),
            'answeredQuestions' => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Answered')),
            'imageCount'        => count(array_filter($questions, fn($q) => !empty($q['image'])))
        ]);
    }
}
