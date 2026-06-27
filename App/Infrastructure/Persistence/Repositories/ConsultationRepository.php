<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Entities\Consultation;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;

final class ConsultationRepository implements ConsultationRepositoryInterface
{
    private static array $storage = [];

    public function save(Consultation $consultation): void
    {
        self::$storage[$consultation->getId()] = $consultation;
    }

    public function findById(string $id): ?Consultation
    {
        return self::$storage[$id] ?? null;
    }

    public function findPendingByExpert(string $expertId): array
    {
        // TODO: implement expert-specific pending consultation lookup.
        return array_values(self::$storage);
    }
}
