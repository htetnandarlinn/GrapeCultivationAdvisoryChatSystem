<?php

namespace App\Presentation\Controllers\Consultation;

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Application\ConsultationManagement\AskQuestion\AskQuestionRequestValidator;
use App\Shared\Exceptions\ValidationException;

class ConsultationController
{
    public function __construct(
        private AskQuestionHandler $handler
    ) {
    }

    /**
     * Display Ask Question Form
     */
    public function create(): void
    {
        require BASE_PATH . '/App/Presentation/Views/farmer/ask-question.php';
    }

    /**
     * Save Question
     */
    public function store(): void
    {
        try {

            $payload = $_POST;

            // Farmer ID from session
            $payload['farmer_id'] = $_SESSION['user']['id'] ?? 0;

            // Temporary image
            $payload['image'] = null;

            // Validate request
            $validator = new AskQuestionRequestValidator();

            $command = $validator->validate($payload);

            // Save question
            $this->handler->handle($command);

            $_SESSION['success'] = 'Question submitted successfully.';

            redirect('/farmer-dashboard');

        } catch (ValidationException $exception) {

            $_SESSION['errors'] = $exception->getErrors();

            $_SESSION['old'] = $_POST;

            redirect('/farmer-dashboard/ask-question');
        }
    }
}