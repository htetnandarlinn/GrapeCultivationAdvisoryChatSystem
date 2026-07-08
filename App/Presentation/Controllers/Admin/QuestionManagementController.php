<?php

namespace App\Presentation\Controllers\Admin;

use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;

class QuestionManagementController
{
    use AuthorizesPermissions;

    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
    ) {}

    #[Permission('questions.view', 'View All Questions')]
    public function index(): void
    {
        $this->authorize('questions.view');
        $questions = $this->questionRepository->findAll();

        View::render(
            'admin/questionManagement',
            [
                'activePage' => 'questions',
                'questions' => $questions
            ]
        );
    }

    #[Permission('questions.view', 'View All Questions')]
    public function view(): void
    {
        $this->authorize('questions.view');
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

        View::render(
            'admin/question-view',
            [
                'activePage' => 'questions',
                'question' => $question
            ]
        );
    }
}
