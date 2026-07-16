<?php

namespace App\Domain\ConsultationManagement\Entities;

use App\Domain\ConsultationManagement\ValueObjects\PayoutStatus;

final class ExpertPayout
{
    private ?int $id;
    private int $consultationId;
    private ?int $paymentId;
    private int $expertId;
    private int $farmerId;
    private float $grossAmount;
    private float $platformFee;
    private float $netAmount;
    private PayoutStatus $status;
    private ?\DateTimeImmutable $releasedAt;
    private ?string $releasedBy;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        int $consultationId,
        ?int $paymentId,
        int $expertId,
        int $farmerId,
        float $grossAmount,
        float $platformFee,
        float $netAmount,
        ?PayoutStatus $status = null,
        ?\DateTimeImmutable $releasedAt = null,
        ?string $releasedBy = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = $id;
        $this->consultationId = $consultationId;
        $this->paymentId = $paymentId;
        $this->expertId = $expertId;
        $this->farmerId = $farmerId;
        $this->grossAmount = $grossAmount;
        $this->platformFee = $platformFee;
        $this->netAmount = $netAmount;
        $this->status = $status ?? PayoutStatus::pending();
        $this->releasedAt = $releasedAt;
        $this->releasedBy = $releasedBy;
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

    public function getConsultationId(): int
    {
        return $this->consultationId;
    }

    public function getPaymentId(): ?int
    {
        return $this->paymentId;
    }

    public function getExpertId(): int
    {
        return $this->expertId;
    }

    public function getFarmerId(): int
    {
        return $this->farmerId;
    }

    public function getGrossAmount(): float
    {
        return $this->grossAmount;
    }

    public function getPlatformFee(): float
    {
        return $this->platformFee;
    }

    public function getNetAmount(): float
    {
        return $this->netAmount;
    }

    public function getStatus(): PayoutStatus
    {
        return $this->status;
    }

    public function getReleasedAt(): ?\DateTimeImmutable
    {
        return $this->releasedAt;
    }

    public function getReleasedBy(): ?string
    {
        return $this->releasedBy;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isReleased(): bool
    {
        return $this->status->isReleased();
    }

    public function markReleased(string $releasedBy): void
    {
        $this->status = PayoutStatus::released();
        $this->releasedAt = new \DateTimeImmutable();
        $this->releasedBy = $releasedBy;
        $this->updatedAt = new \DateTimeImmutable();
    }
}
