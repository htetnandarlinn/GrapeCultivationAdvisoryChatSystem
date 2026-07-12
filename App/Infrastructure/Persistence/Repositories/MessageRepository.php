<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Messaging\Entities\Message;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;
use PDO;

final class MessageRepository implements MessageRepositoryInterface
{
    public function __construct(private PDO $connection) {}

    public function save(Message $message): int
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO messages (consultation_id, sender_id, message, message_type, reply_to, caption, created_at)
             VALUES (:consultation_id, :sender_id, :message, :message_type, :reply_to, :caption, NOW())'
        );
        $stmt->execute([
            ':consultation_id' => $message->consultationId,
            ':sender_id' => $message->senderId,
            ':message' => $message->message,
            ':message_type' => $message->type->getValue(),
            ':reply_to' => $message->replyTo,
            ':caption' => $message->caption,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    public function findByConversationId(int $consultationId): array
    {
        $stmt = $this->connection->prepare('
            SELECT m.*, u.username as sender_name,
                   rm.message as reply_to_message,
                   ru.username as reply_to_sender
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.user_id
            LEFT JOIN messages rm ON m.reply_to = rm.message_id
            LEFT JOIN users ru ON rm.sender_id = ru.user_id
            WHERE m.consultation_id = :consultation_id
            ORDER BY m.created_at ASC
        ');
        $stmt->execute([':consultation_id' => $consultationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $messageId): ?array
    {
        $stmt = $this->connection->prepare('
            SELECT m.*, u.username as sender_name
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.user_id
            WHERE m.message_id = :message_id
            LIMIT 1
        ');
        $stmt->execute([':message_id' => $messageId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function findLastMessageByConsultationIds(array $consultationIds): array
    {
        if (empty($consultationIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($consultationIds), '?'));
        $stmt = $this->connection->prepare("
            SELECT m.*, u.username as sender_name
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.user_id
            WHERE m.message_id IN (
                SELECT MAX(m2.message_id) FROM messages m2
                WHERE m2.consultation_id IN ($placeholders)
                GROUP BY m2.consultation_id
            )
        ");
        $stmt->execute(array_values($consultationIds));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['consultation_id']] = $row;
        }

        return $result;
    }
}