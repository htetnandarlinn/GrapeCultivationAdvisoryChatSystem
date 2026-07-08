<?php

namespace App\Presentation\Controllers\Expert;

use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\ExpertView;

class ExpertDashboardController
{
    use AuthorizesPermissions;
    private QuestionRepository $questionRepository;

    public function __construct()
    {
        $this->questionRepository = new QuestionRepository();
    }

    #[Permission('expert.dashboard.view', 'View Expert Dashboard')]
    public function index(): void
    {
        $this->authorize('expert.dashboard.view');
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