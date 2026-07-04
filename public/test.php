<?php
require __DIR__ . '/../vendor/autoload.php';

new App\Presentation\Controllers\Consultation\ConsultationController(
    new App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler(
        new App\Infrastructure\Persistence\Repositories\QuestionRepository()
    )
);

echo "OK";