<?php

namespace App\Presentation\Controllers\Expert;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\ExpertView;

class AnswerQuestionPageController
{
    use AuthorizesPermissions;
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    #[Permission('expert.questions.answer', 'Answer Question')]
    public function index(): void
    {
        $this->authorize('expert.questions.answer');
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
