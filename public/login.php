<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);

if (!defined('BASE_URL')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    define('BASE_URL', rtrim($scriptDir, '/') === '/' ? '' : rtrim($scriptDir, '/'));
}

$baseUrl = BASE_URL === '/' ? '' : BASE_URL;

// Public wrapper to include the auth login view from App/Presentation/Views
include __DIR__ . '/../App/Presentation/Views/auth/login.php';
