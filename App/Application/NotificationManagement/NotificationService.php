<?php

namespace App\Application\NotificationManagement;

use App\Domain\NotificationManagement\Entities\Notification;
use App\Domain\NotificationManagement\Repositories\NotificationRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;

final class NotificationService
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepo,
        private UserRepositoryInterface $userRepository,
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
        foreach ($this->userRepository->findByType('admin') as $admin) {
            $this->notify($admin->getId(), 'admin', $message, $type, $link);
        }
    }

    public function notifyAllByRole(
        string $role,
        string $message,
        string $type,
        ?string $link = null,
    ): void {
        foreach ($this->userRepository->findByType($role) as $user) {
            $this->notify($user->getId(), $role, $message, $type, $link);
        }
    }
}
