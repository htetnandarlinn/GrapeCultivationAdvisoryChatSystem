<?php

namespace App\Domain\Messaging\Services;

use App\Domain\Messaging\Entities\Message;

final class ChatDomainService
{
    public function createMessageThread(string $conversationId, array $participants): void
    {
        // TODO: Implement message thread creation.
    }

    public function sendMessage(Message $message): void
    {
        // TODO: implement domain-specific message rules.
    }
}

