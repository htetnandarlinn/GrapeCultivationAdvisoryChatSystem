<?php

namespace App\Presentation\Controllers\Farmer;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Views\FarmerView;

class FarmerDashboardController
{
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    public function index(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect('/login');
        }

        $farmerId = (int) $_SESSION['user_id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        $totalQuestions = count($questions);
        $pendingQuestions = 0;
        $answeredQuestions = 0;
        $imageCount = 0;

        foreach ($questions as $q) {
            if (($q['status_name'] ?? '') === 'Pending') {
                $pendingQuestions++;
            }

            if (($q['status_name'] ?? '') === 'Answered') {
                $answeredQuestions++;
            }

            if (!empty($q['image'])) {
                $imageCount++;
            }
        }

        FarmerView::render('farmer/farmer-dashboard', [
            'activePage' => 'dashboard',
            'questions' => $questions,
            'totalQuestions' => $totalQuestions,
            'pendingQuestions' => $pendingQuestions,
            'answeredQuestions' => $answeredQuestions,
            'imageCount' => $imageCount
        ]);
    }

    public function askQuestion(): void
    {
        FarmerView::render('farmer/ask-question', [
            'activePage' => 'ask'
        ]);
    }

    public function submitQuestion(): void
    {
        FarmerView::render('farmer/question-submitted', [
            'activePage' => 'submit'
        ]);
    }

    public function totalQuestions(): void
    {
        if (!isset($_SESSION['user'])) {
            redirect('/login');
        }

        $farmerId = (int) $_SESSION['user']['user_id'];

        $questions = $this->questionRepository->findByFarmer($farmerId);

        echo count($questions);
    }
}