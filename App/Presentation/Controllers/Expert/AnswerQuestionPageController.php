<?php

namespace App\Presentation\Controllers\Expert;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Views\ExpertView;

class AnswerQuestionPageController
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

        $questionId = (int) ($_GET['id'] ?? 0);

        if ($questionId <= 0) {
            redirect('/expert-dashboard');
            return;
        }

        $question = $this->questionRepository->findById($questionId);

        if (!$question) {
            redirect('/expert-dashboard');
            return;
        }

        ExpertView::render(
            'expert/answer-question',
            [
                'activePage' => 'expert-dashboard',
                'question' => $question
            ]
        );
    }
}
