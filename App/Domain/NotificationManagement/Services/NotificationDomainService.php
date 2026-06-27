<?php

namespace App\Domain\NotificationManagement\Services;

use App\Domain\NotificationManagement\Entities\Notification;

final class NotificationDomainService
{
    public function send(Notification $notification): void
    {
        // TODO: implement notification sending policies.
    }
}

