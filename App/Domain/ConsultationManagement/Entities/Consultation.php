<?php

namespace App\Domain\ConsultationManagement\Entities;

use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;

final class Consultation
{
    private string $id;
    private string $userId;
    private string $question;
    private ConsultationStatus $status;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id, string $userId, string $question)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->question = $question;
        $this->status = ConsultationStatus::pending();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getStatus(): ConsultationStatus
    {
        return $this->status;
    }

    public function markAnswered(): void
    {
        $this->status = ConsultationStatus::answered();
    }
}

