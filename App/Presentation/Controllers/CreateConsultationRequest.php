<?php

namespace App\Presentation\Controllers;

final class CreateConsultationRequest
{
    public function __construct(
        public readonly string $id,
        public readonly string $userId,
        public readonly string $question,
    ) {
    }
}
