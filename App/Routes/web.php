<?php

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Controllers\Auth\AuthController;
use App\Presentation\Controllers\Consultation\ConsultationController;
use App\Presentation\Controllers\Dashboard\DashboardController;
use App\Presentation\Controllers\Farmer\FarmerDashboardController;
use App\Presentation\Controllers\Farmer\ProfileController;
use App\Routes\Router;

$router = new Router();

/* ================= AUTH ================= */

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/logout', [AuthController::class, 'logout']);

/* ================= EMAIL ================= */

$router->get('/verify-email', [AuthController::class, 'verifyEmail']);

/* ================= HOME ================= */

$router->get('/', [DashboardController::class, 'home']);

/* ================= FARMER DASHBOARD ================= */

$router->get(
    '/farmer-dashboard',
    [FarmerDashboardController::class, 'index']
);

$router->get(
    '/farmer-dashboard/total-questions',
    [FarmerDashboardController::class, 'totalQuestions']
);

/* ================= CONSULTATION ================= */

$router->get(
    '/farmer-dashboard/ask-question',
    [ConsultationController::class, 'create']
);

$router->post(
    '/farmer-dashboard/ask-question',
    [ConsultationController::class, 'store']
);

/* ================= PROFILE ================= */

$router->get('/profile', [ProfileController::class, 'profile']);
$router->get('/profile/edit', [ProfileController::class, 'edit']);
$router->post('/profile/update', [ProfileController::class, 'update']);

return $router;