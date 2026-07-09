<?php

namespace App\Application\NotificationManagement;

use App\Domain\NotificationManagement\Entities\Notification;
use App\Domain\NotificationManagement\Repositories\NotificationRepositoryInterface;
use PDO;

final class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepo,
        private PDO $connection,
    ) {}

    public function notify(
        int $recipientId,
        string $recipientRole,
        string $message,
        string $type,
        ?string $link = null,
    ): void {
        $notification = new Notification(
            id: null,
            recipientId: $recipientId,
            recipientRole: $recipientRole,
            message: $message,
            type: $type,
            link: $link,
        );

        $this->notificationRepo->save($notification);
    }

    public function notifyAllAdmins(
        string $message,
        string $type,
        ?string $link = null,
    ): void {
        $stmt = $this->connection->prepare("
            SELECT u.user_id FROM users u
            JOIN master_data md ON md.id = u.user_type_id
            WHERE LOWER(md.code) = 'admin' AND md.category = 'USER_TYPE'
        ");
        $stmt->execute();
        $adminIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($adminIds as $adminId) {
            $this->notify((int) $adminId, 'admin', $message, $type, $link);
        }
    }

    public function notifyAllByRole(
        string $role,
        string $message,
        string $type,
        ?string $link = null,
    ): void {
        $stmt = $this->connection->prepare("
            SELECT u.user_id FROM users u
            JOIN master_data md ON md.id = u.user_type_id
            WHERE LOWER(md.code) = :role AND md.category = 'USER_TYPE'
        ");
        $stmt->execute([':role' => $role]);
        $userIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($userIds as $userId) {
            $this->notify((int) $userId, $role, $message, $type, $link);
        }
    }
}
