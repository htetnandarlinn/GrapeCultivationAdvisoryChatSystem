<?php

namespace App\Presentation\Controllers;

class AccessDeniedController
{
    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        http_response_code(403);
        $message = $_SESSION['access_denied_message'] ?? 'You do not have permission to access this page.';
        unset($_SESSION['access_denied_message']);

        require dirname(__DIR__, 3) . '/App/Presentation/Views/access-denied.php';
    }
}
