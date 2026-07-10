<?php

namespace App\Presentation\Controllers\Chat;

use PDO;

class ChatController
{
    public function __construct(private PDO $connection) {}

    public function history(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $consultationId = (int) ($_GET['consultation_id'] ?? 0);
        if (!$consultationId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing consultation_id']);
            return;
        }

        $stmt = $this->connection->prepare('
            SELECT m.*, u.username as sender_name,
                   rm.message as reply_to_message,
                   ru.username as reply_to_sender
            FROM messages m
            LEFT JOIN users u ON m.sender_id = u.user_id
            LEFT JOIN messages rm ON m.reply_to = rm.message_id
            LEFT JOIN users ru ON rm.sender_id = ru.user_id
            WHERE m.consultation_id = :cid
            ORDER BY m.created_at ASC
        ');
        $stmt->execute([':cid' => $consultationId]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json');
        echo json_encode($messages);
    }

    public function send(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $consultationId = (int) ($_POST['consultation_id'] ?? 0);
        $message = trim($_POST['message'] ?? '');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $hasImage = !empty($_FILES['image']['tmp_name']);
        $replyTo = !empty($_POST['reply_to']) ? (int) $_POST['reply_to'] : null;
        $caption = trim($_POST['caption'] ?? '');

        if (!$consultationId || (!$message && !$hasImage)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing fields']);
            return;
        }

        $messageType = 'text';

        if ($hasImage) {
            $uploadDir = __DIR__ . '/../../../../public/uploads/chat/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $consultationId . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $message = 'uploads/chat/' . $filename;
            $messageType = 'image';
        }

        $stmt = $this->connection->prepare('INSERT INTO messages (consultation_id, sender_id, message, message_type, reply_to, caption, created_at) VALUES (:cid, :sid, :msg, :type, :reply_to, :caption, NOW())');
        $stmt->execute([
            ':cid' => $consultationId,
            ':sid' => $userId,
            ':msg' => $message,
            ':type' => $messageType,
            ':reply_to' => $replyTo,
            ':caption' => $caption ?: null,
        ]);

        $messageId = (int) $this->connection->lastInsertId();

        $stmt = $this->connection->prepare('SELECT username FROM users WHERE user_id = :id');
        $stmt->execute([':id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Notify the other participant
        $senderRole = $_SESSION['user_role'] ?? '';
        $senderName = $user['username'] ?? 'Someone';
        $consultStmt = $this->connection->prepare('SELECT farmer_id, expert_id FROM consultations WHERE id = :cid');
        $consultStmt->execute([':cid' => $consultationId]);
        $consult = $consultStmt->fetch(PDO::FETCH_ASSOC);
        if ($consult) {
            $preview = mb_strlen($message) > 60 ? mb_substr($message, 0, 60) . '...' : $message;
            if ($senderRole === 'farmer') {
                if (!empty($consult['expert_id'])) {
                    notify(
                        (int) $consult['expert_id'],
                        'expert',
                        "$senderName sent a message in consultation #$consultationId",
                        'message_received',
                        '/expert/consultations/hub'
                    );
                }
            } else {
                notify(
                    (int) $consult['farmer_id'],
                    'farmer',
                    "$senderName sent a message in your consultation",
                    'message_received',
                    '/consultations'
                );
            }
        }

        $replyToMessage = null;
        $replyToSender = null;
        if ($replyTo) {
            $stmt = $this->connection->prepare('SELECT m.message, u.username FROM messages m LEFT JOIN users u ON m.sender_id = u.user_id WHERE m.message_id = :id');
            $stmt->execute([':id' => $replyTo]);
            $replyRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($replyRow) {
                $replyToMessage = $replyRow['message'];
                $replyToSender = $replyRow['username'];
            }
        }

        header('Content-Type: application/json');
        echo json_encode([
            'id' => $messageId,
            'message' => $message,
            'message_type' => $messageType,
            'sender_id' => $userId,
            'sender_name' => $user ? $user['username'] : 'Unknown',
            'reply_to' => $replyTo,
            'reply_to_message' => $replyToMessage,
            'reply_to_sender' => $replyToSender,
            'caption' => $caption ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
