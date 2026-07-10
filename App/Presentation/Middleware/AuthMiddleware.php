<?php

namespace App\Presentation\Middleware;

class AuthMiddleware implements MiddlewareInterface
{
    public function handle(): void
    {
        if (empty($_SESSION['user'])) {
            \redirect('/login');
        }
    }
}
