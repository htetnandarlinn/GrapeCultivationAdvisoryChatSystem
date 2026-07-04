<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\Question;

interface QuestionRepositoryInterface
{
    public function save(Question $question): bool;

    public function findById(int $questionId): ?Question;

    public function findByFarmer(int $farmerId): array;

    public function findPending(): array;

    public function answerQuestion(
        int $questionId,
        int $expertId,
        string $answer,
        int $statusId
    ): bool;
}