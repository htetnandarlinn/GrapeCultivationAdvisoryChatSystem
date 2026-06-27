<?php

namespace App\Domain\ConsultationManagement\Events;

final class ConsultationCreated
{
    public function __construct(
        public readonly string $consultationId,
        public readonly string $userId,
        public readonly string $question,
    ) {
    }
}

