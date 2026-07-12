<?php

namespace App\Domain\Messaging\Entities;

use App\Domain\Messaging\ValueObjects\MessageType;

final class Message
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $consultationId,
        public readonly int $senderId,
        public readonly string $message,
        public readonly MessageType $type,
        public readonly ?int $replyTo = null,
        public readonly ?string $caption = null,
        public readonly ?\DateTimeImmutable $createdAt = null,
    ) {}

    public static function text(
        int $consultationId,
        int $senderId,
        string $message,
        ?int $replyTo = null,
    ): self {
        return new self(
            id: null,
            consultationId: $consultationId,
            senderId: $senderId,
            message: $message,
            type: MessageType::text(),
            replyTo: $replyTo,
        );
    }

    public static function image(
        int $consultationId,
        int $senderId,
        string $imagePath,
        ?string $caption = null,
        ?int $replyTo = null,
    ): self {
        return new self(
            id: null,
            consultationId: $consultationId,
            senderId: $senderId,
            message: $imagePath,
            type: MessageType::image(),
            replyTo: $replyTo,
            caption: $caption,
        );
    }
}

