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
    private ?string $paymentMethod;
    private ?string $transactionImage;
    private ?string $verifiedBy;
    private ?\DateTimeImmutable $verifiedAt;
    private ?string $refundStatus;
    private ?\DateTimeImmutable $refundDate;
    private ?float $refundAmount;
    private ?string $adminNotes;
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
        ?string $paymentMethod = null,
        ?string $transactionImage = null,
        ?string $verifiedBy = null,
        ?\DateTimeImmutable $verifiedAt = null,
        ?string $refundStatus = null,
        ?\DateTimeImmutable $refundDate = null,
        ?float $refundAmount = null,
        ?string $adminNotes = null,
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
        $this->paymentMethod = $paymentMethod;
        $this->transactionImage = $transactionImage;
        $this->verifiedBy = $verifiedBy;
        $this->verifiedAt = $verifiedAt;
        $this->refundStatus = $refundStatus;
        $this->refundDate = $refundDate;
        $this->refundAmount = $refundAmount;
        $this->adminNotes = $adminNotes;
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

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function getTransactionImage(): ?string
    {
        return $this->transactionImage;
    }

    public function setPaymentMethod(?string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function setTransactionImage(?string $transactionImage): void
    {
        $this->transactionImage = $transactionImage;
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

    public function markExpertAccepted(): void
    {
        $this->status = ConsultationStatus::expertAccepted();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markAwaitingPayment(): void
    {
        $this->status = ConsultationStatus::awaitingPayment();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markPaymentSubmitted(string $idempotencyKey, string $paymentMethod, string $transactionImage): void
    {
        $now = new \DateTimeImmutable();
        $this->status = ConsultationStatus::paymentSubmitted();
        $this->idempotencyKey = $idempotencyKey;
        $this->paymentMethod = $paymentMethod;
        $this->transactionImage = $transactionImage;
        $this->updatedAt = $now;
    }

    public function markPaymentApproved(string $verifiedBy): void
    {
        $now = new \DateTimeImmutable();
        $this->status = ConsultationStatus::accepted();
        $this->paidAt = $now;
        $this->expiresAt = $now->modify('+30 days');
        $this->verifiedBy = $verifiedBy;
        $this->verifiedAt = $now;
        $this->updatedAt = $now;
    }

    public function markChatStarted(): void
    {
        $this->status = ConsultationStatus::chatStarted();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markCompleted(): void
    {
        $this->status = ConsultationStatus::completed();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markClosed(): void
    {
        $this->status = ConsultationStatus::closed();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markExpired(): void
    {
        $this->status = ConsultationStatus::expired();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function markRefunded(float $amount, ?string $adminNotes = null): void
    {
        $now = new \DateTimeImmutable();
        $this->refundStatus = 'refunded';
        $this->refundDate = $now;
        $this->refundAmount = $amount;
        $this->adminNotes = $adminNotes;
        $this->paidAt = null;
        $this->expiresAt = null;
        $this->idempotencyKey = null;
        $this->verifiedBy = null;
        $this->verifiedAt = null;
        $this->status = ConsultationStatus::awaitingPayment();
        $this->updatedAt = $now;
    }

    public function setAdminNotes(?string $adminNotes): void
    {
        $this->adminNotes = $adminNotes;
    }
}
