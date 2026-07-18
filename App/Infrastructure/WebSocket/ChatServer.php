<?php

namespace App\Infrastructure\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface
{
    private array $clients = [];
    private array $rooms = [];

    public function onOpen(ConnectionInterface $conn): void
    {
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $params);
        $consultationId = (int) ($params['consultation_id'] ?? 0);
        $userId = (int) ($params['user_id'] ?? 0);
        $role = $params['role'] ?? '';

        if (!$consultationId || !$userId) {
            $conn->close();
            return;
        }

        $conn->consultationId = $consultationId;
        $conn->userId = $userId;
        $conn->role = $role;

        $this->clients[$conn->resourceId] = $conn;
        $this->rooms[$consultationId][$conn->resourceId] = $conn;

        $this->broadcastToRoom($consultationId, [
            'type' => 'system',
            'message' => ($role === 'farmer' ? 'Farmer' : 'Expert') . ' has joined the consultation.',
        ]);
    }

    public function onMessage(ConnectionInterface $from, $msg): void
    {
        $data = json_decode($msg, true);
        if (!$data) return;

        if (($data['type'] ?? '') === 'status_update') {
            $this->broadcastToRoom($from->consultationId, [
                'type' => 'status_update',
                'consultation_id' => $from->consultationId,
                'status' => $data['status'] ?? '',
            ]);
            return;
        }

        if (empty($data['message'])) return;

        $this->broadcastToRoom($from->consultationId, [
            'type' => 'message',
            'message_id' => $data['message_id'] ?? null,
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text',
            'image_path' => $data['image_path'] ?? null,
            'reply_to' => $data['reply_to'] ?? null,
            'reply_to_message' => $data['reply_to_message'] ?? null,
            'reply_to_sender' => $data['reply_to_sender'] ?? null,
            'caption' => $data['caption'] ?? null,
            'sender_id' => $from->userId,
            'sender_name' => $data['sender_name'] ?? 'Unknown',
            'sender_role' => $from->role,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function onClose(ConnectionInterface $conn): void
    {
        if (isset($conn->consultationId)) {
            unset($this->rooms[$conn->consultationId][$conn->resourceId]);
            if (empty($this->rooms[$conn->consultationId])) {
                unset($this->rooms[$conn->consultationId]);
            }

            $this->broadcastToRoom($conn->consultationId, [
                'type' => 'system',
                'message' => ($conn->role === 'farmer' ? 'Farmer' : 'Expert') . ' has left the consultation.',
            ]);
        }
        unset($this->clients[$conn->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e): void
    {
        echo "[ChatServer Error] " . $e->getMessage() . "\n";
        $conn->close();
    }

    private function broadcastToRoom(int $consultationId, array $data): void
    {
        $encoded = json_encode($data);
        if (!isset($this->rooms[$consultationId])) return;
        foreach ($this->rooms[$consultationId] as $client) {
            $client->send($encoded);
        }
    }
}
