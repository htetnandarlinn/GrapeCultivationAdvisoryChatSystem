<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\Consultation;

interface ConsultationRepositoryInterface
{
    public function save(Consultation $consultation): void;

    public function findById(string $id): ?Consultation;

    public function findPendingByExpert(string $expertId): array;
}

