<?php

namespace App\Application\ConsultationManagement\AskQuestion;

use App\Domain\ConsultationManagement\Entities\Question;
use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;

final class AskQuestionHandler
{
    public function __construct(
        private QuestionRepositoryInterface $questionRepository
    ) {}

    public function handle(AskQuestionCommand $command): void
    {
        $question = new Question(
            questionId: null,
            farmerId: $command->farmerId,
            categoryId: $command->categoryId,
            title: $command->title,
            description: $command->description,
            image: $command->image,   // ✅ string|null
            statusId: 7
        );

        $this->questionRepository->save($question);
    }
}