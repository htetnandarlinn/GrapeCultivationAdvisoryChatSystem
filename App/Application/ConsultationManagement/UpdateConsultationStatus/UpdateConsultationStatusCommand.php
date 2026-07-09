<?php

namespace App\Application\ConsultationManagement\UpdateConsultationStatus;

final class UpdateConsultationStatusCommand
{
    public function __construct(
        public readonly int $consultationId,
        public readonly string $status,
        public readonly ?int $expertId = null,
        public readonly ?string $rejectionNote = null,
    ) {}
}
