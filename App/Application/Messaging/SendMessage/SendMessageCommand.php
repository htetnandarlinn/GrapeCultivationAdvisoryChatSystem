<?php

namespace App\Application\Messaging\SendMessage;

final class SendMessageCommand
{
    public function __construct(
        public readonly string $id,
        public readonly string $conversationId,
        public readonly string $senderId,
        public readonly string $text
    ) {
    }
}

