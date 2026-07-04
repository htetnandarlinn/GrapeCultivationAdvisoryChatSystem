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
        /**
         * CONSULTATION_STATUS.PENDING = 7
         * (from master_data table)
         */
        $pendingStatusId = 7;

        $question = new Question(
            questionId: null,
            farmerId: $command->farmerId,
            categoryId: $command->categoryId,
            title: $command->title,
            description: $command->description,
            image: $command->image,
            statusId: $pendingStatusId,
            expertId: null,
            answer: null
        );

        $this->questionRepository->save($question);
    }
}