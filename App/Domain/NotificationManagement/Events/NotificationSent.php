<?php

namespace App\Domain\NotificationManagement\Events;

final class NotificationSent
{
    public function __construct(public readonly string $notificationId)
    {
    }
}

