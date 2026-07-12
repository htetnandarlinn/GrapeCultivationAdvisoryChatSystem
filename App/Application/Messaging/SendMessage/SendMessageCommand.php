<?php

namespace App\Application\Messaging\SendMessage;

final class SendMessageCommand
{
    public function __construct(
        public readonly int $consultationId,
        public readonly int $senderId,
        public readonly string $message,
        public readonly string $messageType,
        public readonly ?int $replyTo = null,
        public readonly ?string $caption = null,
    ) {}
}

