<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\Consultation;

interface ConsultationRepositoryInterface
{
    public function save(Consultation $consultation): void;

    public function findById(int $id): ?Consultation;

    public function findAll(): array;

    public function findByFarmer(int $farmerId): array;

    public function findByExpert(int $expertId): array;

    public function findByStatus(string $status): array;

    public function countAll(): int;

    public function update(Consultation $consultation): void;
}
