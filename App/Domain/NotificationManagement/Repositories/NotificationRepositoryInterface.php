<?php

namespace App\Domain\NotificationManagement\Repositories;

use App\Domain\NotificationManagement\Entities\Notification;

interface NotificationRepositoryInterface
{
    public function save(Notification $notification): void;

    public function findByRecipientId(string $recipientId): array;
}

