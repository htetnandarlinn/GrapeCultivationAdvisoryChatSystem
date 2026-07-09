<?php

use App\Application\ConsultationManagement\AskQuestion\AskQuestionHandler;
use App\Infrastructure\Persistence\Repositories\QuestionRepository;
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

/* ================= DASHBOARD ================= */
$router->get('/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/users', [UserManagementController::class, 'index']);

$router->get('/admin/roles', [RoleController::class, 'index']);
$router->get('/admin/roles/create', [RoleController::class, 'create']);
$router->post('/admin/roles/store', [RoleController::class, 'store']);
$router->get('/admin/roles/edit', [RoleController::class, 'edit']);
$router->post('/admin/roles/update', [RoleController::class, 'update']);
$router->post('/admin/roles/delete', [RoleController::class, 'delete']);

$router->post('/admin/users/role', [UserManagementController::class, 'assignRole']);

$router->get('/admin/permissions/sync', [PermissionAssignmentController::class, 'sync']);
$router->get('/admin/roles/permissions', [PermissionAssignmentController::class, 'list']);
$router->post('/admin/roles/permissions/update', [PermissionAssignmentController::class, 'update']);

$router->get('/access-denied', [\App\Presentation\Controllers\AccessDeniedController::class, 'index']);

$router->get('/farmer-dashboard/total-questions', [DashboardController::class, 'totalQuestions']);

$router->get('/admin/questions', [QuestionManagementController::class, 'index']);

$router->get(  '/admin/questions/view', [QuestionManagementController::class, 'view']);


$router->post('/expert/questions/answer', [AnswerQuestionController::class, 'store']);

$router->get(
    '/expert/questions/answer',
    [AnswerQuestionPageController::class, 'index']
);

$router->get('/expert/articles', [ArticleController::class, 'index']);
$router->get('/expert/articles/create', [ArticleController::class, 'create']);
$router->post('/expert/articles/store', [ArticleController::class, 'store']);
$router->get('/expert/articles/edit', [ArticleController::class, 'edit']);
$router->post('/expert/articles/update', [ArticleController::class, 'update']);
$router->post('/expert/articles/delete', [ArticleController::class, 'delete']);

$router->get('/expert/articles/view', [ArticleController::class, 'view']);
$router->post('/expert/articles/accept', [ArticleController::class, 'accept']);
$router->post('/expert/articles/reject', [ArticleController::class, 'reject']);

/* ================= CONSULTATION ================= */

/* Ask Question */
$router->get(
    '/farmer-dashboard/ask-question',
    [DashboardController::class, 'askQuestion']
);

$router->post(
    '/farmer-dashboard/ask-question',
    [ConsultationController::class, 'store']
);

/* Question Submitted Confirmation */
$router->get(
    '/farmer-dashboard/question-submitted',
    [DashboardController::class, 'submitQuestion']
);

/* ================= PROFILE ================= */

$router->get('/profile', [ProfileController::class, 'profile']);
$router->get('/profile/edit', [ProfileController::class, 'edit']);
$router->post('/profile/update', [ProfileController::class, 'update']);

return $router;
