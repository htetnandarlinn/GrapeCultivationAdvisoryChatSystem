<?php

declare(strict_types=1);

define('BASE_PATH', realpath(__DIR__ . '/..'));

require BASE_PATH . '/vendor/autoload.php';

session_start();

/*
|--------------------------------------------------------------------------
| Base URL
|--------------------------------------------------------------------------
*/

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$basePath = rtrim($scriptDir, '/');

define(
    'BASE_URL',
    ($basePath === '' || $basePath === '/')
        ? ''
        : $basePath
);

$baseUrl = BASE_URL;

/*
|--------------------------------------------------------------------------
| Imports
|--------------------------------------------------------------------------
*/

use App\Application\ConsultationManagement\CreateConsultation\CreateConsultationHandler;
use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;

use App\Domain\UserManagement\Services\UserAuthenticationService;

use App\Infrastructure\Persistence\Repositories\AuthRepository;
use App\Infrastructure\Persistence\Repositories\ConsultationRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;

use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Controllers\AuthController;
use App\Presentation\Controllers\ConsultationController;
use App\Presentation\Controllers\CreateConsultationRequestValidator;

use App\Presentation\Http\JsonResponse;

use App\Shared\Exceptions\ValidationException;

/*
|--------------------------------------------------------------------------
| Helper Functions
|--------------------------------------------------------------------------
*/

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function validationErrors(ValidationException $e): array
{
    return $e->getErrors();
}

/*
|--------------------------------------------------------------------------
| Resolve Current Route
|--------------------------------------------------------------------------
*/

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (
    BASE_URL !== '' &&
    str_starts_with($requestUri, BASE_URL)
) {
    $requestUri = substr($requestUri, strlen(BASE_URL));
}

$path = '/' . trim($requestUri, '/');

if ($path === '/index.php') {
    $path = '/';
}

if (str_starts_with($path, '/index.php/')) {
    $path = substr($path, strlen('/index.php'));
}

if (isset($_GET['page'])) {
    $path = '/' . trim($_GET['page'], '/');
}

$action = isset($_GET['action']) ? trim((string) $_GET['action']) : null;

if ($path === '/login/authenticate') {
    $path = '/login';
    $action = 'authenticate';
}

if ($path === '/register/store') {
    $path = '/register';
    $action = 'store';
}

$method = $_SERVER['REQUEST_METHOD'];

/*
|--------------------------------------------------------------------------
| GET Routes
|--------------------------------------------------------------------------
*/

if ($method === 'GET') {

    switch ($path) {

        case '/':
            require __DIR__ . '/home.php';
            exit;

        case '/login':
            require __DIR__ . '/login.php';
            exit;

        case '/register':
            require __DIR__ . '/register.php';
            exit;

        case '/farmer-dashboard':
            require __DIR__ . '/farmerDashboard.php';
            exit;

        case '/expert-dashboard':
            require __DIR__ . '/expertDashboard.php';
            exit;

        case '/admin-dashboard':
            require __DIR__ . '/adminDashboard.php';
            exit;
    }
}

/*
|--------------------------------------------------------------------------
| POST Routes
|--------------------------------------------------------------------------
*/

$routeKey = $method . ' ' . $path . ($action !== null ? ' ' . $action : '');

switch ($routeKey) {

    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    case 'POST /register store':

        $payload = $_POST;

        try {

            $repository = new UserRepository();
            $registerHandler = new RegisterUserHandler($repository);
            $validator = new RegisterRequestValidator();
            $loginValidator = new LoginRequestValidator();
            $authController = new AuthController(
                $registerHandler,
                new LoginUserHandler(new AuthRepository($repository), new UserAuthenticationService()),
                $validator,
                $loginValidator
            );

            $authController->register($payload);

        } catch (ValidationException $e) {

            $_SESSION['errors'] = validationErrors($e);
            $_SESSION['old'] = $payload;
            redirect('/index.php?page=register');

        } catch (Throwable $e) {

            $_SESSION['errors'] = ['registration' => 'Registration could not be completed right now.'];
            $_SESSION['old'] = $payload;
            redirect('/index.php?page=register');

            echo "<pre>";
            echo "Registration Error\n\n";
            echo "Message : " . $e->getMessage();
            echo "\n\nFile : " . $e->getFile();
            echo "\n\nLine : " . $e->getLine();
            echo "\n\nTrace:\n";
            echo $e->getTraceAsString();
            exit;
        }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    case 'POST /login authenticate':

        $payload = $_POST;

        try {

            $repository = new UserRepository();
            $authRepository = new AuthRepository($repository);
            $service = new UserAuthenticationService();
            $validator = new LoginRequestValidator();
            $registerValidator = new RegisterRequestValidator();
            $loginHandler = new LoginUserHandler(
                $authRepository,
                $service
            );
            $authController = new AuthController(
                new RegisterUserHandler($repository),
                $loginHandler,
                $registerValidator,
                $validator
            );

            $authController->authenticate($payload);

        } catch (ValidationException $e) {

            $_SESSION['errors'] = validationErrors($e);
            $_SESSION['old'] = [
                'username_or_email' => $payload['username_or_email'] ?? ''
            ];
            redirect('/index.php?page=login');

        } catch (Throwable $e) {

            $_SESSION['errors'] = ['login' => 'Login could not be completed right now.'];
            $_SESSION['old'] = [
                'username_or_email' => $payload['username_or_email'] ?? ''
            ];
            redirect('/index.php?page=login');

            echo "<pre>";
            echo "Login Error\n\n";
            echo "Message : " . $e->getMessage();
            echo "\n\nFile : " . $e->getFile();
            echo "\n\nLine : " . $e->getLine();
            echo "\n\nTrace:\n";
            echo $e->getTraceAsString();
            exit;
        }

    /*
    |--------------------------------------------------------------------------
    | Consultation API
    |--------------------------------------------------------------------------
    */

    case 'POST /consultations':

        try {

            $payload = json_decode(
                file_get_contents('php://input'),
                true
            ) ?? [];

            $repository = new ConsultationRepository();

            $handler = new CreateConsultationHandler($repository);

            $validator = new CreateConsultationRequestValidator();

            $controller = new ConsultationController($handler);

            $request = $validator->validate($payload);

            $controller->create($request);

            JsonResponse::send([
                'status' => 'created',
                'consultation_id' => $request->id
            ], 201);

        } catch (ValidationException $e) {

            JsonResponse::error($e->getMessage(), 400);

        } catch (Throwable $e) {

            JsonResponse::error($e->getMessage(), 500);
        }

        break;

    default:

        http_response_code(404);

        echo '404 Not Found';
}