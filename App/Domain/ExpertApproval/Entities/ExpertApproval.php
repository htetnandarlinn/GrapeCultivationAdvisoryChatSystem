<?php

namespace App\Domain\ExpertApproval\Entities;

use App\Domain\ExpertApproval\ValueObjects\ExpertStatus;

final class ExpertApproval
{
    private string $id;
    private string $expertId;
    private ExpertStatus $status;
    private \DateTimeImmutable $requestedAt;

    public function __construct(string $id, string $expertId)
    {
        $this->id = $id;
        $this->expertId = $expertId;
        $this->status = ExpertStatus::pending();
        $this->requestedAt = new \DateTimeImmutable();
    }

    public function approve(): void
    {
        $this->status = ExpertStatus::approved();
    }

    public function reject(): void
    {
        $this->status = ExpertStatus::rejected();
    }
}

