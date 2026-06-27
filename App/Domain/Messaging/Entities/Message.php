<?php

namespace App\Domain\Messaging\Entities;

use App\Domain\Messaging\ValueObjects\MessageType;

final class Message
{
    private string $id;
    private string $conversationId;
    private string $senderId;
    private string $text;
    private MessageType $type;
    private \DateTimeImmutable $sentAt;

    public function __construct(
        string $id,
        string $conversationId,
        string $senderId,
        string $text,
        MessageType $type
    ) {
        $this->id = $id;
        $this->conversationId = $conversationId;
        $this->senderId = $senderId;
        $this->text = $text;
        $this->type = $type;
        $this->sentAt = new \DateTimeImmutable();
    }
}

