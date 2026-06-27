<?php

namespace App\Application\Messaging\SendMessage;

use App\Domain\Messaging\Entities\Message;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use App\Domain\Messaging\ValueObjects\MessageType;

final class SendMessageHandler
{
    public function __construct(private MessageRepositoryInterface $messageRepository)
    {
    }

    public function handle(SendMessageCommand $command): void
    {
        $message = new Message(
            $command->id,
            $command->conversationId,
            $command->senderId,
            $command->text,
            MessageType::text(),
        );

        $this->messageRepository->save($message);
    }
}

