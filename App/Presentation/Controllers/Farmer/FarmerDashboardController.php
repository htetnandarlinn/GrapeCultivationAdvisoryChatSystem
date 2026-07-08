<?php

namespace App\Presentation\Controllers\Farmer;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\FarmerView;

class FarmerDashboardController
{
    use AuthorizesPermissions;
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    /**
     * Farmer Dashboard
     */
    #[Permission('farmer.dashboard.view', 'View Farmer Dashboard')]
    public function index(): void
    {
        $this->authorize('farmer.dashboard.view');
        $farmerId = (int) $_SESSION['user']['id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        $totalQuestions = count($questions);
        $pendingQuestions = 0;
        $answeredQuestions = 0;
        $imageCount = 0;

        foreach ($questions as $question) {

            if (($question['status_name'] ?? '') === 'Pending') {
                $pendingQuestions++;
            }

            if (($question['status_name'] ?? '') === 'Answered') {
                $answeredQuestions++;
            }

            if (!empty($question['image'])) {
                $imageCount++;
            }
        }

        FarmerView::render('farmer/farmer-dashboard', [
            'activePage'        => 'dashboard',
            'questions'         => $questions,
            'totalQuestions'    => $totalQuestions,
            'pendingQuestions'  => $pendingQuestions,
            'answeredQuestions' => $answeredQuestions,
            'imageCount'        => $imageCount
        ]);
    }

    /**
     * Show Ask Question Page
     */
    #[Permission('farmer.questions.ask', 'Ask Question')]
    public function askQuestion(): void
    {
        $this->authorize('farmer.questions.ask');
        $categories = $this->questionRepository->findCategories();

        FarmerView::render('farmer/ask-question', [
            'activePage' => 'ask',
            'categories' => $categories
        ]);
    }

    /**
     * Question Submitted Page
     */
    #[Permission('farmer.questions.ask', 'Ask Question')]
    public function submitQuestion(): void
    {
        $this->authorize('farmer.questions.ask');
        FarmerView::render('farmer/question-submitted', [
            'activePage' => 'submit'
        ]);
    }

    /**
     * Total Questions
     */
    #[Permission('farmer.questions.view', 'View My Questions')]
    public function totalQuestions(): void
    {
        $this->authorize('farmer.questions.view');
        $farmerId = (int) $_SESSION['user']['id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        FarmerView::render('farmer/farmer-dashboard', [
            'activePage'        => 'dashboard',
            'questions'         => $questions,
            'totalQuestions'    => count($questions),
            'pendingQuestions'  => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Pending')),
            'answeredQuestions' => count(array_filter($questions, fn($q) => ($q['status_name'] ?? '') === 'Answered')),
            'imageCount'        => count(array_filter($questions, fn($q) => !empty($q['image'])))
        ]);
    }
}