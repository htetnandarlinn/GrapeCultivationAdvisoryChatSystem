<?php

namespace App\Domain\NotificationManagement\Entities;

use App\Domain\NotificationManagement\ValueObjects\NotificationType;

final class Notification
{
    private string $id;
    private string $recipientId;
    private string $message;
    private NotificationType $type;
    private bool $isRead = false;
    private \DateTimeImmutable $createdAt;

    public function __construct(string $id, string $recipientId, string $message, NotificationType $type)
    {
        $this->id = $id;
        $this->recipientId = $recipientId;
        $this->message = $message;
        $this->type = $type;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function markRead(): void
    {
        $this->isRead = true;
    }
}

