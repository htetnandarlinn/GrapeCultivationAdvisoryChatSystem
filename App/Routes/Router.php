<?php

namespace App\Routes;

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Application\RoleManagement\RoleService;
use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Infrastructure\Persistence\Repositories\RoleRepository;
use App\Presentation\Controllers\Admin\RoleController;
use App\Presentation\Controllers\Consultation\ConsultationController;
use App\Presentation\Controllers\Expert\AnswerQuestionController;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    private function addRoute(string $httpMethod, string $uri, callable|array $action): void
    {
        $uri = rtrim($uri, '/') ?: '/';
        $this->routes[$httpMethod][$uri] = $action;
    }

    public function dispatch(string $httpMethod, string $requestUri): void
    {
        $path = parse_url($requestUri, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        if (defined('BASE_URL') && BASE_URL !== '' && str_starts_with($path, BASE_URL)) {
            $path = substr($path, strlen(BASE_URL)) ?: '/';
        }

        if (!isset($this->routes[$httpMethod][$path])) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

        $action = $this->routes[$httpMethod][$path];

        /* -----------------------------
           Callable route
        ------------------------------ */
        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        [$controller, $method] = $action;

        /* -----------------------------
           Class-based controller
        ------------------------------ */
        if (!class_exists($controller)) {
            throw new \Exception("Controller class {$controller} not found.");
        }

        $instance = $this->resolveController($controller);

        if (!method_exists($instance, $method)) {
            throw new \Exception("Method {$method} not found in {$controller}.");
        }

        $instance->$method();
    }

    /**
     * 🔥 SIMPLE MANUAL DEPENDENCY INJECTION
     */
    private function resolveController(string $controller)
    {
        return match ($controller) {
            ConsultationController::class =>
                new ConsultationController(
                    new AskQuestionHandler(
                        new QuestionRepository()
                    )
                ),

            AnswerQuestionController::class =>
                new AnswerQuestionController(),

            RoleController::class =>
                new RoleController(
                    new RoleRepository(),
                    new RoleService(
                        new RoleRepository()
                    )
                ),

            default => new $controller()
        };
    }
}
