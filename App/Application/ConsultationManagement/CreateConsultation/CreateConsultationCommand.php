<?php

namespace App\Application\ConsultationManagement\CreateConsultation;

final class CreateConsultationCommand
{
    public function __construct(
        public readonly int $farmerId,
        public readonly string $title,
        public readonly string $description,
    ) {}
}
