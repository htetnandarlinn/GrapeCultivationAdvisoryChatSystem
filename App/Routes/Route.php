<?php

namespace App\Routes;

final class Route
{
    private ?string $permission = null;
    private ?string $role = null;
    private bool $requireAuth = false;

    private mixed $action;

    public function __construct(
        private string $method,
        private string $uri,
        mixed $action,
    ) {
        $this->action = $action;
    }

    public function can(string $permission): self
    {
        $this->permission = $permission;
        return $this;
    }

    public function role(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function auth(): self
    {
        $this->requireAuth = true;
        return $this;
    }

    public function authorize(): void
    {
        $hasAuth = !empty($_SESSION['user']);

        if ($this->requireAuth || $this->role !== null || $this->permission !== null) {
            if (!$hasAuth) {
                \redirect('/login');
            }
        }

        if ($this->role !== null) {
            $userRole = $_SESSION['user_role'] ?? '';
            if ($userRole !== $this->role) {
                \redirect('/access-denied');
            }
        }

        if ($this->permission !== null) {
            if (!\can($this->permission)) {
                \redirect('/access-denied');
            }
        }
    }

    public function getAction(): mixed
    {
        return $this->action;
    }
}
