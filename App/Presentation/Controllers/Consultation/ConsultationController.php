<?php

namespace App\Presentation\Controllers\Consultation;

use App\Application\ConsultationManagement\AskQuestion\AskQuestionCommand;
use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Shared\Exceptions\ValidationException;

class ConsultationController
{
    public function __construct(
        private AskQuestionHandler $handler
    ) {}

    public function create(): void
    {
        require BASE_PATH . '/App/Presentation/Views/farmer/ask-question.php';
    }

    public function store(): void
    {
        try {
            $farmerId   = (int)($_SESSION['user']['id'] ?? 0);
            $categoryId = (int)($_POST['category_id'] ?? 0);
            $title      = trim($_POST['title'] ?? '');
            $description= trim($_POST['description'] ?? '');

            /* ---------- IMAGE UPLOAD (FIX) ---------- */
            $imageName = null;

            if (!empty($_FILES['image']['name'])) {
                $uploadDir = BASE_PATH . '/public/uploads/questions/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = uniqid('q_', true) . '.' . $ext;

                move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $uploadDir . $imageName
                );
            }
            /* --------------------------------------- */

            $command = new AskQuestionCommand(
                farmerId: $farmerId,
                categoryId: $categoryId,
                title: $title,
                description: $description,
                image: $imageName   // ✅ STRING
            );

            $this->handler->handle($command);

            $_SESSION['success'] = 'Question submitted successfully.';
            redirect('/farmer-dashboard/question-submitted');

        } catch (ValidationException $e) {

            $_SESSION['errors'] = $e->getErrors();
            redirect('/farmer-dashboard/ask-question');
        }
    }
}