<?php

namespace App\Routes;

final class Route
{
    private ?string $permission = null;
    private array $roles = [];
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

    public function role(string|array $role): self
    {
        $this->roles = is_array($role) ? $role : [$role];
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

        if ($this->requireAuth || !empty($this->roles) || $this->permission !== null) {
            if (!$hasAuth) {
                \redirect('/login');
            }
        }

        if (!empty($this->roles)) {
            $userRole = $_SESSION['user_role'] ?? '';
            if (!in_array($userRole, $this->roles, true)) {
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
