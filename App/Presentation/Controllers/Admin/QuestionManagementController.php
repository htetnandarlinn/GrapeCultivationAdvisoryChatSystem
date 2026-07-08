<?php

namespace App\Presentation\Controllers\Admin;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\AdminView;

class QuestionManagementController
{
    use AuthorizesPermissions;
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    #[Permission('admin.questions.view', 'View All Questions')]
    public function index(): void
    {
        $this->authorize('admin.questions.view');
        $questions = $this->questionRepository->findAll();

        AdminView::render(
            'admin/questionManagement',
            [
                'activePage' => 'questions',
                'questions' => $questions
            ]
        );
    }

    #[Permission('admin.questions.view', 'View All Questions')]
    public function view(): void
    {
        $this->authorize('admin.questions.view');
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
