<?php

namespace App\Presentation\Controllers\Admin;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Views\AdminView;

class QuestionManagementController
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

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        $questions = $this->questionRepository->findAll();

        AdminView::render(
            'admin/questionManagement',
            [
                'activePage' => 'questions',
                'questions' => $questions
            ]
        );
    }

    public function view(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            return;
        }

        $questionId = (int) ($_GET['id'] ?? 0);

        if ($questionId <= 0) {
            redirect('/admin/questions');
            return;
        }

        $question = $this->questionRepository->findById($questionId);

        if ($question === null) {
            redirect('/admin/questions');
            return;
        }

        AdminView::render(
            'admin/question-view',
            [
                'activePage' => 'questions',
                'question' => $question
            ]
        );
    }
}
