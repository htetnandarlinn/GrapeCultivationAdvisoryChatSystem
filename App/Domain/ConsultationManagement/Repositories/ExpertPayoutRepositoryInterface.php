<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\ExpertPayout;

interface ExpertPayoutRepositoryInterface
{
    public function save(ExpertPayout $payout): void;

    public function findById(int $id): ?ExpertPayout;

    public function findByConsultationId(int $consultationId): ?ExpertPayout;

    public function findByExpertId(int $expertId): array;

    public function findAll(): array;

    public function sumNetByExpertId(int $expertId): float;

    public function sumPendingNet(): float;

    public function sumReleasedNet(): float;
}
