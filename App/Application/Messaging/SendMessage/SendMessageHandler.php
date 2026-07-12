<?php

namespace App\Application\Messaging\SendMessage;

use App\Domain\Messaging\Entities\Message;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use App\Domain\Messaging\ValueObjects\MessageType;

final class SendMessageHandler
{
    public function __construct(private MessageRepositoryInterface $messageRepository) {}

    public function handle(SendMessageCommand $command): int
    {
        if ($command->messageType === 'image') {
            $message = Message::image(
                consultationId: $command->consultationId,
                senderId: $command->senderId,
                imagePath: $command->message,
                caption: $command->caption,
                replyTo: $command->replyTo,
            );
        } else {
            $message = Message::text(
                consultationId: $command->consultationId,
                senderId: $command->senderId,
                message: $command->message,
                replyTo: $command->replyTo,
            );
        }

        return $this->messageRepository->save($message);
    }
}

