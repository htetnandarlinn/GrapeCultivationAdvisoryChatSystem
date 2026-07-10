<?php

namespace App\Routes;

use App\Presentation\Middleware\AuthMiddleware;
use App\Presentation\Middleware\MiddlewareInterface;
use App\Presentation\Middleware\PermissionMiddleware;
use App\Presentation\Middleware\RoleMiddleware;

final class Route
{
    /** @var MiddlewareInterface[] */
    private array $middleware = [];

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
        $this->middleware[] = new PermissionMiddleware($permission);
        return $this;
    }

    public function role(string|array $role): self
    {
        $this->middleware[] = new RoleMiddleware($role);
        return $this;
    }

    public function auth(): self
    {
        $this->middleware[] = new AuthMiddleware();
        return $this;
    }

    public function authorize(): void
    {
        foreach ($this->middleware as $mw) {
            $mw->handle();
        }
    }

    public function getAction(): mixed
    {
        return $this->action;
    }
}
