<?php

namespace App\Presentation\Controllers\Expert;

use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;

class AnswerQuestionController
{
    use AuthorizesPermissions;

    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
    ) {}

    #[Permission('questions.answer', 'Answer Question')]
    public function store(): void
    {
        $this->authorize('questions.answer');
        $questionId = (int) ($_POST['question_id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');

        $expertId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($expertId <= 0) {
            die('Expert ID not found in session.');
        }

        if ($questionId <= 0) {
            $_SESSION['error'] = 'Invalid question.';
            redirect('/dashboard');
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
        redirect('/dashboard');
    }
}
