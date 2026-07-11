<?php

namespace App\Presentation\Controllers;

use App\Domain\NotificationManagement\Repositories\NotificationRepositoryInterface;
use App\Presentation\Views\View;

class NotificationController
{
    public function __construct(
        private NotificationRepositoryInterface $notificationRepo,
    ) {}

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $role = $_SESSION['user_role'] ?? '';

        if (!$userId) {
            redirect('/login');
            return;
        }

        $this->notificationRepo->markAllAsRead($userId);

        $notifications = $this->notificationRepo->findByRecipientId($userId, 100);

        View::render('notification/index', [
            'notifications' => $notifications,
            'activePage' => 'notifications',
        ], 'dashboard');
    }

    public function unreadCount(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        header('Content-Type: application/json');
        echo json_encode([
            'count' => $userId ? $this->notificationRepo->countUnread($userId) : 0,
        ]);
    }

    public function list(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if (!$userId) {
            header('Content-Type: application/json');
            echo json_encode([]);
            return;
        }

        $notifications = $this->notificationRepo->findByRecipientId($userId, 10);

        $data = array_map(fn($n) => [
            'id' => $n->getId(),
            'message' => $n->getMessage(),
            'type' => $n->getType(),
            'link' => $n->getLink(),
            'is_read' => $n->isRead(),
            'time_ago' => $n->getTimeAgo(),
        ], $notifications);

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function markRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $id = (int) ($_POST['id'] ?? 0);

        if ($userId && $id) {
            $this->notificationRepo->markAsRead($id, $userId);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }

    public function markAllRead(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $userId = (int) ($_SESSION['user']['id'] ?? 0);

        if ($userId) {
            $this->notificationRepo->markAllAsRead($userId);
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
    }
}
