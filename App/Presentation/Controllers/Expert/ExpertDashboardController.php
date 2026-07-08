<?php

namespace App\Presentation\Controllers\Expert;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Views\ExpertView;

class ExpertDashboardController
{
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'expert') {
            redirect('/access-denied');
            return;
        }

        $pendingQuestions = $this->questionRepository->findPending();

        $totalAssigned = count($pendingQuestions);
        $pending = count($pendingQuestions);

        $answered = 0;
        $totalConsultations = 0;

        ExpertView::render(
            'expert/expert-dashboard',
            [
                'activePage' => 'expert-dashboard',
                'questions' => $pendingQuestions,
                'totalAssigned' => $totalAssigned,
                'pending' => $pending,
                'answered' => $answered,
                'totalConsultations' => $totalConsultations,
            ]
        );
    }
}