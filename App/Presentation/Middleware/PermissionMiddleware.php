<?php

namespace App\Presentation\Middleware;

class PermissionMiddleware implements MiddlewareInterface
{
    private string $permission;

    public function __construct(string $permission)
    {
        $this->permission = $permission;
    }

    public function handle(): void
    {
        if (!\can($this->permission)) {
            \redirect('/access-denied');
        }
    }
}
