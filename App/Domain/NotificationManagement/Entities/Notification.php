<?php

namespace App\Domain\NotificationManagement\Entities;

use App\Domain\NotificationManagement\ValueObjects\NotificationType;

final class Notification
{
    private ?int $id;
    private int $recipientId;
    private string $recipientRole;
    private string $message;
    private string $type;
    private ?string $link;
    private bool $isRead;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        ?int $id,
        int $recipientId,
        string $recipientRole,
        string $message,
        string $type,
        ?string $link = null,
        bool $isRead = false,
        ?\DateTimeImmutable $createdAt = null,
    ) {
        $this->id = $id;
        $this->recipientId = $recipientId;
        $this->recipientRole = $recipientRole;
        $this->message = $message;
        $this->type = $type;
        $this->link = $link;
        $this->isRead = $isRead;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipientId(): int
    {
        return $this->recipientId;
    }

    public function getRecipientRole(): string
    {
        return $this->recipientRole;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function isRead(): bool
    {
        return $this->isRead;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function markRead(): void
    {
        $this->isRead = true;
    }

    public function getTimeAgo(): string
    {
        $now = new \DateTimeImmutable();
        $diff = $now->getTimestamp() - $this->createdAt->getTimestamp();

        return match (true) {
            $diff < 60 => 'just now',
            $diff < 3600 => floor($diff / 60) . 'm ago',
            $diff < 86400 => floor($diff / 3600) . 'h ago',
            $diff < 604800 => floor($diff / 86400) . 'd ago',
            default => $this->createdAt->format('M j'),
        };
    }
}

