<?php

namespace App\Presentation\Controllers\Expert;

use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;

class AnswerQuestionPageController
{
    use AuthorizesPermissions;

    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
    ) {}

    #[Permission('questions.answer', 'Answer Question')]
    public function index(): void
    {
        $this->authorize('questions.answer');
        $questionId = (int) ($_GET['id'] ?? 0);

        if ($questionId <= 0) {
            redirect('/dashboard');
            return;
        }

        $question = $this->questionRepository->findById($questionId);

        if (!$question) {
            redirect('/dashboard');
            return;
        }

        View::render(
            'expert/answer-question',
            [
                'activePage' => 'expert-dashboard',
                'question' => $question
            ]
        );
    }
}
