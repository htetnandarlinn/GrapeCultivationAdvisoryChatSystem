<?php

namespace App\Presentation\Controllers;

trait AuthorizesPermissions
{
    private function authorize(string $permission): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user'])) {
            redirect('/login');
            exit;
        }

        if (!can($permission)) {
            redirect('/access-denied');
            exit;
        }
    }
}
