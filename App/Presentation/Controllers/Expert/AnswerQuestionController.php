<?php

namespace App\Presentation\Controllers\Expert;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;

class AnswerQuestionController
{
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'expert') {
            redirect('/access-denied');
            return;
        }

        $questionId = (int) ($_POST['question_id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');

        // Correct session key
        $expertId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($expertId <= 0) {
            die('Expert ID not found in session.');
        }

        if ($questionId <= 0) {
            $_SESSION['error'] = 'Invalid question.';
            redirect('/expert-dashboard');
            return;
        }

        if ($answer === '') {
            $_SESSION['error'] = 'Answer is required.';
            redirect('/expert/questions/answer?id=' . $questionId);
            return;
        }

        $this->questionRepository->answerQuestion(
            $questionId,
            $expertId,
            $answer,
            8
        );

        $_SESSION['success'] = 'Question answered successfully.';
        redirect('/expert-dashboard');
    }
}
