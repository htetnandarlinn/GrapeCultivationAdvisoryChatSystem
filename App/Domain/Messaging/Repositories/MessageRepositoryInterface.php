<?php

namespace App\Domain\Messaging\Repositories;

use App\Domain\Messaging\Entities\Message;

interface MessageRepositoryInterface
{
    public function save(Message $message): int;

    /** @return array<int, array{id: int, consultation_id: int, sender_id: int, message: string, message_type: string, reply_to: ?int, caption: ?string, created_at: string, sender_name: string, reply_to_message: ?string, reply_to_sender: ?string}> */
    public function findByConversationId(int $consultationId): array;

    public function findById(int $messageId): ?array;

    /** @return array<int, array{message_id: int, consultation_id: int, sender_id: int, message: string, message_type: string, reply_to: ?int, caption: ?string, created_at: string, sender_name: string}> */
    public function findLastMessageByConsultationIds(array $consultationIds): array;
}

