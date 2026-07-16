<?php

namespace App\Domain\ConsultationManagement\Entities;

use App\Domain\ConsultationManagement\ValueObjects\PaymentStatus;

final class Payment
{
    public function __construct(
        private ?int $id,
        private int $consultationId,
        private int $farmerId,
        private float $amount,
        private PaymentStatus $status,
        private ?\DateTimeImmutable $paymentDate = null,
        private ?\DateTimeImmutable $startDate = null,
        private ?\DateTimeImmutable $expiryDate = null,
        private ?string $transactionReference = null,
        private ?string $paymentMethod = null,
        private ?string $transactionImage = null,
        private ?string $verifiedBy = null,
        private ?\DateTimeImmutable $verifiedAt = null,
        private ?string $refundStatus = null,
        private ?\DateTimeImmutable $refundDate = null,
        private ?float $refundAmount = null,
        private ?string $adminNotes = null,
        private ?\DateTimeImmutable $createdAt = null,
        private ?\DateTimeImmutable $updatedAt = null,
    ) {}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConsultationId(): int
    {
        return $this->consultationId;
    }

    public function getFarmerId(): int
    {
        return $this->farmerId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function getPaymentDate(): ?\DateTimeImmutable
    {
        return $this->paymentDate;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getExpiryDate(): ?\DateTimeImmutable
    {
        return $this->expiryDate;
    }

    public function getTransactionReference(): ?string
    {
        return $this->transactionReference;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getTransactionImage(): ?string
    {
        return $this->transactionImage;
    }

    public function getVerifiedBy(): ?string
    {
        return $this->verifiedBy;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function getRefundStatus(): ?string
    {
        return $this->refundStatus;
    }

    public function getRefundDate(): ?\DateTimeImmutable
    {
        return $this->refundDate;
    }

    public function getRefundAmount(): ?float
    {
        return $this->refundAmount;
    }

    public function getAdminNotes(): ?string
    {
        return $this->adminNotes;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
