<?php

namespace App\Domain\ConsultationManagement\Repositories;

interface ConsultationImageRepositoryInterface
{
    /**
     * @param array<int> $consultationIds
     * @return array<int, array<int, array<string, mixed>>> map of consultationId => list of image rows
     */
    public function findByConsultationIds(array $consultationIds): array;

    /**
     * @return array<int, array<string, mixed>> list of image rows for a single consultation
     */
    public function findByConsultationId(int $consultationId): array;

    public function findFirstImagePath(int $consultationId): ?string;

    public function create(int $consultationId, string $imagePath): void;
}
