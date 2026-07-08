<?php

namespace App\Domain\UserManagement\Entities;

final class EmailVerification
{
    private ?int $id;

    private int $userId;

    private string $token;

    private \DateTimeImmutable $expiresAt;

    private ?\DateTimeImmutable $verifiedAt;

    private \DateTimeImmutable $createdAt;

    public function __construct(
        ?int $id,
        int $userId,
        string $token,
        \DateTimeImmutable $expiresAt,
        ?\DateTimeImmutable $verifiedAt = null,
        ?\DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id;
        $this->userId = $userId;
        $this->token = $token;
        $this->expiresAt = $expiresAt;
        $this->verifiedAt = $verifiedAt;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function isVerified(): bool
    {
        return $this->verifiedAt !== null;
    }

    public function isExpired(): bool
    {
        return new \DateTimeImmutable() > $this->expiresAt;
    }

    public function markAsVerified(): void
    {
        $this->verifiedAt = new \DateTimeImmutable();
    }
}