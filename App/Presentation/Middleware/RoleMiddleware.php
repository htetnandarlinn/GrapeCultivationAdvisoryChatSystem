<?php

namespace App\Presentation\Middleware;

class RoleMiddleware implements MiddlewareInterface
{
    private array $roles;

    public function __construct(string|array $roles)
    {
        $this->roles = is_array($roles) ? $roles : [$roles];
    }

    public function handle(): void
    {
        $userRole = $_SESSION['user_role'] ?? '';
        if (!in_array($userRole, $this->roles, true)) {
            \redirect('/access-denied');
        }
    }
}
