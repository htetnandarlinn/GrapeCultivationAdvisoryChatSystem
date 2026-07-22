<?php

namespace App\Presentation\Controllers\Chat;

use App\Application\Messaging\GetConversationHistory\GetConversationHistoryHandler;
use App\Application\Messaging\GetConversationHistory\GetConversationHistoryQuery;
use App\Application\Messaging\SendMessage\SendMessageCommand;
use App\Application\Messaging\SendMessage\SendMessageHandler;
use App\Application\NotificationManagement\NotificationService;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\Messaging\Repositories\MessageRepositoryInterface;

class ChatController
{
    public function __construct(
        private SendMessageHandler $sendMessageHandler,
        private GetConversationHistoryHandler $getHistoryHandler,
        private MessageRepositoryInterface $messageRepository,
        private NotificationService $notificationService,
        private ConsultationRepositoryInterface $consultationRepository,
    ) {}

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

        $messages = $this->getHistoryHandler->handle(
            new GetConversationHistoryQuery($consultationId)
        );

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
        $messageText = trim($_POST['message'] ?? '');
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $hasImage = !empty($_FILES['image']['tmp_name']);
        $replyTo = !empty($_POST['reply_to']) ? (int) $_POST['reply_to'] : null;
        $caption = trim($_POST['caption'] ?? '');

        if (!$consultationId || (!$messageText && !$hasImage)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing fields']);
            return;
        }

        $consultation = $this->consultationRepository->findById($consultationId);
        if (!$consultation) {
            http_response_code(404);
            echo json_encode(['error' => 'Consultation not found']);
            return;
        }

        $this->expireIfPastDue($consultation);

        $chatStatus = $consultation->getStatus()->getValue();
        $isExpired = $consultation->isExpired();
        if (!in_array($chatStatus, ['accepted', 'chat_started'], true) || $isExpired) {
            http_response_code(403);
            echo json_encode([
                'error' => $isExpired
                    ? 'Your one-month consultation period has ended. Please renew your payment to continue.'
                    : 'Chat is not available for this consultation.'
            ]);
            return;
        }

        $messageType = 'text';
        $finalMessage = $messageText;

        if ($hasImage) {
            $uploadDir = __DIR__ . '/../../../../public/uploads/chat/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $filename = $consultationId . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $finalMessage = 'uploads/chat/' . $filename;
            $messageType = 'image';
        }

        $command = new SendMessageCommand(
            consultationId: $consultationId,
            senderId: $userId,
            message: $finalMessage,
            messageType: $messageType,
            replyTo: $replyTo,
            caption: $caption ?: null,
        );

        $messageId = $this->sendMessageHandler->handle($command);

        $senderName = $_SESSION['user']['username'] ?? 'Someone';

        if ($consultation) {
            $senderRole = $_SESSION['user_role'] ?? '';

            if ($senderRole === 'farmer' && $consultation->getExpertId()) {
                $this->notificationService->notify(
                    $consultation->getExpertId(),
                    'expert',
                    "$senderName sent a message in consultation #$consultationId",
                    'message_received',
                    '/expert/consultations/chat?id=' . $consultationId
                );
            } elseif ($senderRole !== 'farmer') {
                $this->notificationService->notify(
                    $consultation->getFarmerId(),
                    'farmer',
                    "$senderName sent a message in your consultation",
                    'message_received',
                    '/consultations?id=' . $consultationId
                );
            }

            // Notify admins that a message was sent in a consultation
            $this->notificationService->notifyAllAdmins(
                "$senderName ($senderRole) sent a message in consultation #$consultationId",
                'message_received',
                '/notifications'
            );
        }

        $savedMessage = $this->messageRepository->findById($messageId);

        header('Content-Type: application/json');
        echo json_encode([
            'id' => $savedMessage['message_id'] ?? null,
            'message' => $finalMessage,
            'message_type' => $messageType,
            'sender_id' => $userId,
            'sender_name' => $savedMessage['sender_name'] ?? $senderName,
            'reply_to' => $replyTo,
            'reply_to_message' => $savedMessage['reply_to_message'] ?? null,
            'reply_to_sender' => $savedMessage['reply_to_sender'] ?? null,
            'caption' => $caption ?: null,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function expireIfPastDue(\App\Domain\ConsultationManagement\Entities\Consultation $consultation): void
    {
        $status = $consultation->getStatus()->getValue();
        if (in_array($status, ['accepted', 'chat_started'], true) && $consultation->isExpired()) {
            $consultation->markExpired();
            $this->consultationRepository->update($consultation);
        }
    }
}