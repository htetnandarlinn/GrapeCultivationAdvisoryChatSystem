<?php

namespace App\Application\ConsultationManagement\CreateConsultation;

final class CreateConsultationCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $question
    ) {
    }
}

