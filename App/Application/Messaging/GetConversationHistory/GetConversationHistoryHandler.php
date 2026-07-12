<?php

namespace App\Application\Messaging\GetConversationHistory;

use App\Domain\Messaging\Repositories\MessageRepositoryInterface;

final class GetConversationHistoryHandler
{
    public function __construct(private MessageRepositoryInterface $messageRepository) {}

    public function handle(GetConversationHistoryQuery $query): array
    {
        return $this->messageRepository->findByConversationId($query->consultationId);
    }
}