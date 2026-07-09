<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\NotificationManagement\Entities\Notification;
use App\Domain\NotificationManagement\Repositories\NotificationRepositoryInterface;
use PDO;

final class NotificationRepository implements NotificationRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function save(Notification $notification): ?int
    {
        $stmt = $this->connection->prepare("
            INSERT INTO notifications (recipient_id, recipient_role, type, message, link, is_read, created_at)
            VALUES (:recipient_id, :recipient_role, :type, :message, :link, :is_read, :created_at)
        ");
        $stmt->execute([
            ':recipient_id' => $notification->getRecipientId(),
            ':recipient_role' => $notification->getRecipientRole(),
            ':type' => $notification->getType(),
            ':message' => $notification->getMessage(),
            ':link' => $notification->getLink(),
            ':is_read' => (int) $notification->isRead(),
            ':created_at' => $notification->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function findByRecipientId(int $recipientId, int $limit = 20): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM notifications
            WHERE recipient_id = :recipient_id
            ORDER BY created_at DESC
            LIMIT :limit
        ");
        $stmt->bindValue(':recipient_id', $recipientId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(fn(array $row): Notification => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findUnreadByRecipientId(int $recipientId): array
    {
        $stmt = $this->connection->prepare("
            SELECT * FROM notifications
            WHERE recipient_id = :recipient_id AND is_read = 0
            ORDER BY created_at DESC
            LIMIT 50
        ");
        $stmt->execute([':recipient_id' => $recipientId]);

        return array_map(fn(array $row): Notification => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countUnread(int $recipientId): int
    {
        $stmt = $this->connection->prepare("
            SELECT COUNT(*) FROM notifications
            WHERE recipient_id = :recipient_id AND is_read = 0
        ");
        $stmt->execute([':recipient_id' => $recipientId]);

        return (int) $stmt->fetchColumn();
    }

    public function markAsRead(int $notificationId, int $recipientId): void
    {
        $stmt = $this->connection->prepare("
            UPDATE notifications SET is_read = 1
            WHERE id = :id AND recipient_id = :recipient_id
        ");
        $stmt->execute([':id' => $notificationId, ':recipient_id' => $recipientId]);
    }

    public function markAllAsRead(int $recipientId): void
    {
        $stmt = $this->connection->prepare("
            UPDATE notifications SET is_read = 1
            WHERE recipient_id = :recipient_id AND is_read = 0
        ");
        $stmt->execute([':recipient_id' => $recipientId]);
    }

    private function toEntity(array $row): Notification
    {
        return new Notification(
            id: (int) $row['id'],
            recipientId: (int) $row['recipient_id'],
            recipientRole: $row['recipient_role'],
            message: $row['message'],
            type: $row['type'],
            link: $row['link'] ?? null,
            isRead: (bool) $row['is_read'],
            createdAt: new \DateTimeImmutable($row['created_at']),
        );
    }
}
