<?php

namespace App\Domain\Messaging\Events;

final class MessageSent
{
    public function __construct(public readonly string $messageId, public readonly string $conversationId)
    {
    }
}

