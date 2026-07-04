<?php

namespace App\Application\ConsultationManagement\AskQuestion;

final class AskQuestionCommand
{
    public function __construct(
        public int $farmerId,
        public int $categoryId,
        public string $title,
        public string $description,
        public ?array $image
    ) {}
}