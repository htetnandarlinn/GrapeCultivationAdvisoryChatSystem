<?php

namespace App\Domain\ConsultationManagement\Entities;

use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;

final class Consultation
{
    private ?int $id;
    private int $farmerId;
    private string $title;
    private string $description;
    private ConsultationStatus $status;
    private ?int $expertId;
    private ?string $rejectionNote;
    private ?\DateTimeImmutable $paidAt;
    private ?\DateTimeImmutable $expiresAt;
    private ?string $idempotencyKey;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        int $farmerId,
        string $title,
        string $description,
        ?ConsultationStatus $status = null,
        ?int $expertId = null,
        ?string $rejectionNote = null,
        ?\DateTimeImmutable $paidAt = null,
        ?\DateTimeImmutable $expiresAt = null,
        ?string $idempotencyKey = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = $id;
        $this->farmerId = $farmerId;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status ?? ConsultationStatus::pending();
        $this->expertId = $expertId;
        $this->rejectionNote = $rejectionNote;
        $this->paidAt = $paidAt;
        $this->expiresAt = $expiresAt;
        $this->idempotencyKey = $idempotencyKey;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getFarmerId(): int
    {
        return $this->farmerId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): ConsultationStatus
    {
        return $this->status;
    }

    public function getExpertId(): ?int
    {
        return $this->expertId;
    }

    public function getRejectionNote(): ?string
    {
        return $this->rejectionNote;
    }

    public function getPaidAt(): ?\DateTimeImmutable
    {
        return $this->paidAt;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function assignExpert(int $expertId): void
    {
        $this->expertId = $expertId;
        $this->status = ConsultationStatus::assigned();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAwaitingPayment(): void
    {
        $this->status = ConsultationStatus::awaitingPayment();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markPaid(string $idempotencyKey): void
    {
        $now = new \DateTimeImmutable();
        $this->status = ConsultationStatus::accepted();
        $this->paidAt = $now;
        $this->expiresAt = $now->modify('+30 days');
        $this->idempotencyKey = $idempotencyKey;
        $this->updatedAt = $now;
    }

    public function markExpired(): void
    {
        $this->status = ConsultationStatus::expired();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function renewPayment(string $idempotencyKey): void
    {
        $now = new \DateTimeImmutable();
        $this->status = ConsultationStatus::accepted();
        $this->paidAt = $now;
        $this->expiresAt = $now->modify('+30 days');
        $this->idempotencyKey = $idempotencyKey;
        $this->updatedAt = $now;
    }

    public function isExpired(): bool
    {
        if ($this->expiresAt === null) {
            return false;
        }
        return $this->expiresAt < new \DateTimeImmutable();
    }

    public function accept(): void
    {
        $this->status = ConsultationStatus::accepted();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function reject(string $note): void
    {
        $this->rejectionNote = $note;
        $this->status = ConsultationStatus::rejected();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAnswered(): void
    {
        $this->status = ConsultationStatus::accepted();
        $this->updatedAt = new \DateTimeImmutable();
    }
}
