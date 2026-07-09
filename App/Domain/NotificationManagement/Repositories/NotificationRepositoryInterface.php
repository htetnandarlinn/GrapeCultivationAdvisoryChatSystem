<?php

namespace App\Domain\NotificationManagement\Repositories;

use App\Domain\NotificationManagement\Entities\Notification;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): ?int;

    public function findByRecipientId(int $recipientId, int $limit = 20): array;

    public function findUnreadByRecipientId(int $recipientId): array;

    public function countUnread(int $recipientId): int;

    public function markAsRead(int $notificationId, int $recipientId): void;

    public function markAllAsRead(int $recipientId): void;
}
