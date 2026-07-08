<?php

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Infrastructure\Persistence\Repositories\QuestionRepository;
use App\Presentation\Controllers\Admin\AdminDashboardController;
use App\Presentation\Controllers\Admin\ExpertManagementController;
use App\Presentation\Controllers\Admin\FarmerManagementController;
use App\Presentation\Controllers\Admin\QuestionManagementController;
use App\Presentation\Controllers\Admin\RoleController;
use App\Presentation\Controllers\Auth\AuthController;
use App\Presentation\Controllers\Auth\ForgotPasswordController;
use App\Presentation\Controllers\Consultation\ConsultationController;
use App\Presentation\Controllers\Dashboard\DashboardController;
use App\Presentation\Controllers\Farmer\FarmerDashboardController;
use App\Presentation\Controllers\Expert\ExpertDashboardController;
use App\Presentation\Controllers\Expert\AnswerQuestionController;
use App\Presentation\Controllers\Farmer\ProfileController;
use App\Presentation\Controllers\Expert\AnswerQuestionPageController;
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

/* ================= FORGOT / RESET PASSWORD ================= */
$router->get('/forgot-password', [ForgotPasswordController::class, 'showForm']);
$router->post('/forgot-password', [ForgotPasswordController::class, 'send']);
$router->get('/reset-password', [ForgotPasswordController::class, 'showReset']);
$router->post('/reset-password', [ForgotPasswordController::class, 'reset']);

/* ================= HOME ================= */

$router->get('/', [DashboardController::class, 'home']);

/* ================= ADMIN DASHBOARD ================= */
$router->get('/admin-dashboard', [AdminDashboardController::class, 'index']);
$router->get('/admin/farmers', [FarmerManagementController::class, 'index']);
$router->get('/admin/farmers/view', [FarmerManagementController::class, 'view']);
$router->post('/admin/farmers/delete', [FarmerManagementController::class, 'delete']);

$router->get('/admin/experts', [ExpertManagementController::class, 'index']);

$router->get('/admin/experts/create', [ExpertManagementController::class, 'create']);
$router->post('/admin/experts/store', [ExpertManagementController::class, 'store']);
$router->get('/admin/experts/edit', [ExpertManagementController::class, 'edit']);
$router->post('/admin/experts/update', [ExpertManagementController::class, 'update']);

$router->get('/admin/experts/view', [ExpertManagementController::class, 'view']);
$router->post('/admin/experts/delete', [ExpertManagementController::class, 'delete']);

$router->get('/admin/roles', [RoleController::class, 'index']);
$router->get('/admin/roles/create', [RoleController::class, 'create']);
$router->post('/admin/roles/store', [RoleController::class, 'store']);
$router->get('/admin/roles/edit', [RoleController::class, 'edit']);
$router->post('/admin/roles/update', [RoleController::class, 'update']);
$router->post('/admin/roles/delete', [RoleController::class, 'delete']);

$router->get('/access-denied', [\App\Presentation\Controllers\AccessDeniedController::class, 'index']);

/* ================= FARMER DASHBOARD ================= */

$router->get('/farmer-dashboard', [FarmerDashboardController::class, 'index']);

$router->get('/farmer-dashboard/total-questions', [FarmerDashboardController::class, 'totalQuestions']);

$router->get('/admin/questions', [QuestionManagementController::class, 'index']);

$router->get(  '/admin/questions/view', [QuestionManagementController::class, 'view']);


$router->get( '/expert-dashboard',[ExpertDashboardController::class,'index']);

$router->post('/expert/questions/answer', [AnswerQuestionController::class, 'store']);

$router->get(
    '/expert/questions/answer',
    [AnswerQuestionPageController::class, 'index']
);

/* ================= CONSULTATION ================= */

/* Ask Question */
$router->get(
    '/farmer-dashboard/ask-question',
    [FarmerDashboardController::class, 'askQuestion']
);

$router->post(
    '/farmer-dashboard/ask-question',
    [ConsultationController::class, 'store']
);

/* Question Submitted Confirmation */
$router->get(
    '/farmer-dashboard/question-submitted',
    [FarmerDashboardController::class, 'submitQuestion']
);

/* ================= PROFILE ================= */

$router->get('/profile', [ProfileController::class, 'profile']);
$router->get('/profile/edit', [ProfileController::class, 'edit']);
$router->post('/profile/update', [ProfileController::class, 'update']);

return $router;
