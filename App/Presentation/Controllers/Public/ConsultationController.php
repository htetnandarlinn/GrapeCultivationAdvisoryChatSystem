<?php

namespace App\Presentation\Controllers\Public;

use App\Application\ConsultationManagement\AskQuestion\AskQuestionCommand;
use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\View;
use App\Shared\Exceptions\ValidationException;

final class ConsultationController
{
    use AuthorizesPermissions;

    public function __construct(
        private QuestionRepositoryInterface $questionRepository,
        private AskQuestionHandler $askQuestionHandler,
    ) {}

    public function ask(): void
    {
        $this->authorize('questions.ask');

        $categories = $this->questionRepository->findCategories();

        View::render('public/ask-question', [
            'categories' => $categories,
        ], 'app');
    }

    public function store(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'farmer') {
            redirect('/access-denied');
            return;
        }

        try {
            $farmerId   = (int)($_SESSION['user']['id'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $title      = trim($_POST['title'] ?? '');
            $description= trim($_POST['description'] ?? '');

            $imageName = null;
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = dirname(__DIR__, 4) . '/public/uploads/questions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('q_', true) . '.' . $ext;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            }

            $command = new AskQuestionCommand(
                farmerId: $farmerId,
                categoryId: $categoryId,
                title: $title,
                description: $description,
                image: $imageName
            );

            $this->askQuestionHandler->handle($command);

            $_SESSION['success'] = 'Question submitted successfully.';
            redirect('/consultation/submitted');

        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            redirect('/consultation/ask');
        }
    }

    public function submitted(): void
    {
        $this->authorize('questions.ask');

        View::render('public/question-submitted', [], 'app');
    }

    #[Permission('questions.view', 'View My Questions')]
    public function myQuestions(): void
    {
        $this->authorize('questions.view');

        $farmerId = (int) $_SESSION['user']['id'];
        $questions = $this->questionRepository->findByFarmer($farmerId);

        View::render('public/my-questions', [
            'questions' => $questions,
        ], 'app');
    }
}
