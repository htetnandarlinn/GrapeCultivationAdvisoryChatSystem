<?php

use App\Presentation\Controllers\Admin\ConsultationController as AdminConsultationController;
use App\Presentation\Controllers\Admin\PermissionAssignmentController;
use App\Presentation\Controllers\Admin\RoleController;
use App\Presentation\Controllers\Admin\UserManagementController;
use App\Presentation\Controllers\Auth\AuthController;
use App\Presentation\Controllers\Auth\ForgotPasswordController;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Controllers\Auth\VerifyEmailController;
use App\Presentation\Controllers\Chat\ChatController;
use App\Presentation\Controllers\Consultation\ConsultationController;
use App\Presentation\Controllers\Dashboard\DashboardController;
use App\Presentation\Controllers\Expert\ArticleController;
use App\Presentation\Controllers\Expert\ConsultationController as ExpertConsultationController;
use App\Presentation\Controllers\Farmer\ProfileController;
use App\Presentation\Controllers\Public\ArticleController as PublicArticleController;
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

/* ================= PUBLIC ARTICLES ================= */

$router->get('/articles', [PublicArticleController::class, 'index']);
$router->get('/articles/view', [PublicArticleController::class, 'view']);

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

$router->get('/expert/articles', [ArticleController::class, 'index']);
$router->get('/expert/articles/create', [ArticleController::class, 'create']);
$router->post('/expert/articles/store', [ArticleController::class, 'store']);
$router->get('/expert/articles/edit', [ArticleController::class, 'edit']);
$router->post('/expert/articles/update', [ArticleController::class, 'update']);
$router->post('/expert/articles/delete', [ArticleController::class, 'delete']);

$router->get('/expert/articles/view', [ArticleController::class, 'view']);
$router->post('/expert/articles/accept', [ArticleController::class, 'accept']);
$router->post('/expert/articles/reject', [ArticleController::class, 'reject']);

/* ================= CONSULTATION (FARMER) ================= */

$router->get('/consultation/create', [ConsultationController::class, 'create']);
$router->post('/consultation/store', [ConsultationController::class, 'store']);
$router->post('/consultation/store-ajax', [ConsultationController::class, 'storeAjax']);
$router->get('/consultation/my-consultations', [ConsultationController::class, 'myConsultations']);
$router->get('/consultations', [ConsultationController::class, 'frontendHistory']);

/* ================= CONSULTATION (ADMIN) ================= */

$router->get('/admin/consultations', [AdminConsultationController::class, 'index']);
$router->get('/admin/consultations/view', [AdminConsultationController::class, 'view']);
$router->post('/admin/consultations/assign', [AdminConsultationController::class, 'assignExpert']);

/* ================= CONSULTATION (EXPERT) ================= */

$router->get('/expert/consultations', [ExpertConsultationController::class, 'index']);
$router->get('/expert/consultations/view', [ExpertConsultationController::class, 'view']);
$router->post('/expert/consultations/accept', [ExpertConsultationController::class, 'accept']);
$router->post('/expert/consultations/reject', [ExpertConsultationController::class, 'reject']);
$router->get('/expert/consultations/chat', [ExpertConsultationController::class, 'chat']);

/* ================= CONSULTATION CHAT ================= */

$router->get('/consultation/chat', [ConsultationController::class, 'chat']);
$router->get('/chat/history', [ChatController::class, 'history']);
$router->post('/chat/send', [ChatController::class, 'send']);

/* ================= PROFILE ================= */

$router->get('/profile', [ProfileController::class, 'profile']);
$router->get('/profile/edit', [ProfileController::class, 'edit']);
$router->post('/profile/update', [ProfileController::class, 'update']);

return $router;
