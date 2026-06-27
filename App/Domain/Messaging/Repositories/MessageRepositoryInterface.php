<?php

namespace App\Domain\Messaging\Repositories;

use App\Domain\Messaging\Entities\Message;

interface MessageRepositoryInterface
{
    public function save(Message $message): void;

    public function findByConversationId(string $conversationId): array;
}

