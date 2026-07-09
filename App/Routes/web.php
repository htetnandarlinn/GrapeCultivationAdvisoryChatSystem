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
use App\Presentation\Controllers\NotificationController;
use App\Presentation\Controllers\Public\ArticleController as PublicArticleController;
use App\Routes\Router;

$router = new Router();

/* ================= AUTH (public) ================= */

$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);

$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/logout', [AuthController::class, 'logout']);

/* ================= EMAIL (public) ================= */

$router->get('/verify-email', [AuthController::class, 'verifyEmail']);

/* ================= FORGOT / RESET PASSWORD (public) ================= */

$router->get('/forgot-password', [ForgotPasswordController::class, 'showForm']);
$router->post('/forgot-password', [ForgotPasswordController::class, 'send']);
$router->get('/reset-password', [ForgotPasswordController::class, 'showReset']);
$router->post('/reset-password', [ForgotPasswordController::class, 'reset']);

/* ================= PUBLIC ARTICLES (public) ================= */

$router->get('/articles', [PublicArticleController::class, 'index']);
$router->get('/articles/view', [PublicArticleController::class, 'view']);

/* ================= HOME (public) ================= */

$router->get('/', [DashboardController::class, 'home']);

/* ================= DASHBOARD (authenticated) ================= */

$router->get('/dashboard', [DashboardController::class, 'index'])->auth();

/* ================= ADMIN: USER MANAGEMENT ================= */

$router->get('/admin/users', [UserManagementController::class, 'index'])->role('admin')->can('users.view');
$router->post('/admin/users/role', [UserManagementController::class, 'assignRole'])->role('admin')->can('users.manage');

/* ================= ADMIN: ROLE MANAGEMENT ================= */

$router->get('/admin/roles', [RoleController::class, 'index'])->role('admin')->can('roles.view');
$router->get('/admin/roles/create', [RoleController::class, 'create'])->role('admin')->can('roles.create');
$router->post('/admin/roles/store', [RoleController::class, 'store'])->role('admin')->can('roles.create');
$router->get('/admin/roles/edit', [RoleController::class, 'edit'])->role('admin')->can('roles.update');
$router->post('/admin/roles/update', [RoleController::class, 'update'])->role('admin')->can('roles.update');
$router->post('/admin/roles/delete', [RoleController::class, 'delete'])->role('admin')->can('roles.delete');

/* ================= ADMIN: PERMISSIONS ================= */

$router->get('/admin/permissions/sync', [PermissionAssignmentController::class, 'sync'])->role('admin')->can('permissions.sync');
$router->get('/admin/roles/permissions', [PermissionAssignmentController::class, 'list'])->role('admin')->can('permissions.update');
$router->post('/admin/roles/permissions/update', [PermissionAssignmentController::class, 'update'])->role('admin')->can('permissions.update');

/* ================= ADMIN: CONSULTATIONS ================= */

$router->get('/admin/consultations', [AdminConsultationController::class, 'index'])->role('admin')->can('consultations.view');
$router->get('/admin/consultations/view', [AdminConsultationController::class, 'view'])->role('admin')->can('consultations.view');
$router->post('/admin/consultations/assign', [AdminConsultationController::class, 'assignExpert'])->role('admin')->can('consultations.assign');

/* ================= EXPERT: ARTICLES ================= */

$router->get('/expert/articles', [ArticleController::class, 'index'])->role(['expert', 'admin'])->can('articles.view');
$router->get('/expert/articles/create', [ArticleController::class, 'create'])->role(['expert', 'admin'])->can('articles.create');
$router->post('/expert/articles/store', [ArticleController::class, 'store'])->role(['expert', 'admin'])->can('articles.create');
$router->get('/expert/articles/edit', [ArticleController::class, 'edit'])->role(['expert', 'admin'])->can('articles.update');
$router->post('/expert/articles/update', [ArticleController::class, 'update'])->role(['expert', 'admin'])->can('articles.update');
$router->post('/expert/articles/delete', [ArticleController::class, 'delete'])->role(['expert', 'admin'])->can('articles.delete');
$router->get('/expert/articles/view', [ArticleController::class, 'view'])->role(['expert', 'admin'])->can('articles.view');
$router->post('/expert/articles/accept', [ArticleController::class, 'accept'])->role(['expert', 'admin'])->can('articles.update');
$router->post('/expert/articles/reject', [ArticleController::class, 'reject'])->role(['expert', 'admin'])->can('articles.update');

/* ================= EXPERT: CONSULTATIONS ================= */

$router->get('/expert/consultations', [ExpertConsultationController::class, 'index'])->role('expert')->can('consultations.answer');
$router->get('/expert/consultations/view', [ExpertConsultationController::class, 'view'])->role('expert')->can('consultations.answer');
$router->post('/expert/consultations/accept', [ExpertConsultationController::class, 'accept'])->role('expert')->can('consultations.answer');
$router->post('/expert/consultations/reject', [ExpertConsultationController::class, 'reject'])->role('expert')->can('consultations.answer');
$router->get('/expert/consultations/chat', [ExpertConsultationController::class, 'chat'])->role('expert')->can('consultations.answer');

/* ================= FARMER: CONSULTATIONS ================= */

$router->get('/consultation/create', [ConsultationController::class, 'create'])->role('farmer');
$router->post('/consultation/store', [ConsultationController::class, 'store'])->role('farmer');
$router->post('/consultation/store-ajax', [ConsultationController::class, 'storeAjax'])->role('farmer');
$router->get('/consultation/my-consultations', [ConsultationController::class, 'myConsultations'])->role('farmer');
$router->get('/consultations', [ConsultationController::class, 'frontendHistory'])->role('farmer');

/* ================= CONSULTATION CHAT (farmer + expert) ================= */

$router->get('/consultation/chat', [ConsultationController::class, 'chat'])->role('farmer');
$router->get('/chat/history', [ChatController::class, 'history'])->auth();
$router->post('/chat/send', [ChatController::class, 'send'])->auth();

/* ================= NOTIFICATIONS (authenticated) ================= */

$router->get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->auth();
$router->get('/notifications/list', [NotificationController::class, 'list'])->auth();
$router->post('/notifications/mark-read', [NotificationController::class, 'markRead'])->auth();
$router->post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->auth();

/* ================= ACCESS DENIED (public) ================= */

$router->get('/access-denied', [\App\Presentation\Controllers\AccessDeniedController::class, 'index']);

/* ================= PROFILE (authenticated) ================= */

$router->get('/profile', [ProfileController::class, 'profile'])->can('profile.view');
$router->get('/profile/edit', [ProfileController::class, 'edit'])->can('profile.view');
$router->post('/profile/update', [ProfileController::class, 'update'])->can('profile.view');

return $router;
