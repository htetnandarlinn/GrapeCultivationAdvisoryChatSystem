<?php

namespace App\Routes;

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationHandler;
use App\Application\Messaging\SendMessage\SendMessageHandler;
use App\Application\PermissionManagement\PermissionRegistrar;
use App\Application\PermissionManagement\PermissionService;
use App\Application\RoleManagement\RoleService;
use App\Application\UserManagement\ForgotPassword\ForgotPasswordHandler;
use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Application\UserManagement\ResetPassword\ResetPasswordHandler;
use App\Infrastructure\Mail\PHPMailerService;
use App\Infrastructure\Persistence\Repositories\PermissionRepository;
use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Infrastructure\Persistence\Repositories\RoleRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Domain\UserManagement\Services\UserAuthenticationService;
use App\Infrastructure\Persistence\Repositories\AuthRepository;
use App\Infrastructure\Persistence\Repositories\PasswordResetRepository;
use App\Infrastructure\Persistence\Repositories\EmailVerificationRepository;
use App\Infrastructure\Persistence\Repositories\ActivityRepository;
use App\Presentation\Controllers\Admin\PermissionAssignmentController;
use App\Presentation\Controllers\Admin\QuestionManagementController;
use App\Presentation\Controllers\Admin\RoleController;
use App\Presentation\Controllers\Admin\UserManagementController;
use App\Presentation\Controllers\Auth\AuthController;
use App\Presentation\Controllers\Auth\ForgotPasswordController;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Controllers\Auth\VerifyEmailController;
use App\Presentation\Controllers\Consultation\ConsultationController;
use App\Presentation\Controllers\Dashboard\DashboardController;

use App\Presentation\Controllers\Expert\AnswerQuestionController;
use App\Presentation\Controllers\Expert\AnswerQuestionPageController;
use App\Presentation\Controllers\Expert\ArticleController;
use App\Presentation\Controllers\Farmer\ProfileController;
use App\Shared\Infrastructure\Database\Database;

class Router
{
    private array $routes = [];

    private ?\PDO $pdo = null;

    private function db(): \PDO
    {
        if ($this->pdo === null) {
            $this->pdo = (new Database())->getConnection();
        }
        return $this->pdo;
    }

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

        if (is_callable($action)) {
            call_user_func($action);
            return;
        }

        [$controller, $method] = $action;

        if (!class_exists($controller)) {
            throw new \Exception("Controller class {$controller} not found.");
        }

        $instance = $this->resolveController($controller);

        if (!method_exists($instance, $method)) {
            throw new \Exception("Method {$method} not found in {$controller}.");
        }

        $instance->$method();
    }

    private function resolveController(string $controller)
    {
        return match ($controller) {
            ConsultationController::class =>
                new ConsultationController(
                    new AskQuestionHandler(
                        new QuestionRepository($this->db())
                    )
                ),

            AnswerQuestionController::class =>
                new AnswerQuestionController(
                    new QuestionRepository($this->db())
                ),

            AnswerQuestionPageController::class =>
                new AnswerQuestionPageController(
                    new QuestionRepository($this->db())
                ),

            QuestionManagementController::class =>
                new QuestionManagementController(
                    new QuestionRepository($this->db())
                ),

            DashboardController::class =>
                new DashboardController(
                    new UserRepository($this->db()),
                    new ActivityRepository($this->db()),
                    new QuestionRepository($this->db())
                ),

            ArticleController::class =>
                new ArticleController(
                    new \App\Infrastructure\Persistence\Repositories\ArticleRepository($this->db())
                ),

            RoleController::class =>
                new RoleController(
                    new RoleRepository($this->db()),
                    new RoleService(
                        new RoleRepository($this->db())
                    )
                ),

            UserManagementController::class =>
                new UserManagementController(
                    new UserRepository($this->db()),
                    new RoleRepository($this->db())
                ),

            PermissionAssignmentController::class =>
                new PermissionAssignmentController(
                    new PermissionService(
                        new PermissionRepository($this->db()),
                        new RoleRepository($this->db())
                    ),
                    new PermissionRegistrar(
                        new PermissionRepository($this->db())
                    ),
                    new RoleRepository($this->db())
                ),

            AuthController::class => $this->createAuthController(),

            ForgotPasswordController::class => $this->createForgotPasswordController(),

            VerifyEmailController::class =>
                new VerifyEmailController(
                    new EmailVerificationRepository($this->db()),
                    new UserRepository($this->db())
                ),

            ProfileController::class =>
                new ProfileController(
                    new UserRepository($this->db()),
                    new \App\Application\UserManagement\UpdateProfile\UpdateProfileHandler(
                        new UserRepository($this->db())
                    )
                ),

            default => new $controller()
        };
    }

    private function createAuthController(): AuthController
    {
        $userRepo = new UserRepository($this->db());
        $mailService = new PHPMailerService();

        return new AuthController(
            new RegisterUserHandler(
                $userRepo,
                $mailService,
                new ActivityRepository($this->db())
            ),
            new LoginUserHandler(
                new AuthRepository($userRepo),
                new UserAuthenticationService()
            ),
            new RegisterRequestValidator(),
            new LoginRequestValidator(),
            $userRepo,
            new ActivityRepository($this->db()),
            new RoleRepository($this->db()),
            new PermissionRepository($this->db())
        );
    }

    private function createForgotPasswordController(): ForgotPasswordController
    {
        $userRepo = new UserRepository($this->db());
        $mailService = new PHPMailerService();
        $passwordResetRepo = new PasswordResetRepository($this->db());

        return new ForgotPasswordController(
            new ForgotPasswordHandler($userRepo, $passwordResetRepo, $mailService),
            new ResetPasswordHandler($passwordResetRepo, $userRepo),
            $passwordResetRepo
        );
    }

}
