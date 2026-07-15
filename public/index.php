<?php

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

session_start();

date_default_timezone_set('Asia/Yangon');

/*
|--------------------------------------------------------------------------
| RELOAD PERMISSIONS (so assigned permissions take effect immediately)
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_role']) && isset($_SESSION['user'])) {
    try {
        $permDb = new \App\Shared\Infrastructure\Database\Database();
        $permConn = $permDb->getConnection();
        $roleRepo = new \App\Infrastructure\Persistence\Repositories\RoleRepository($permConn);
        $permRepo = new \App\Infrastructure\Persistence\Repositories\PermissionRepository($permConn);

        $roles = $roleRepo->findAll();
        $matched = false;
        foreach ($roles as $role) {
            if (strcasecmp($role->getCode(), $_SESSION['user_role']) === 0) {
                $matched = true;
                $permissions = $permRepo->findPermissionsByUserTypeId($role->getId());
                $newPerms = array_map(fn($p) => $p->getKey(), $permissions);

                // If the reload returned permissions, overwrite session.
                // If it returned an empty set, preserve existing session permissions
                // to avoid transient DB glitches removing access unexpectedly.
                if (!empty($newPerms) || !isset($_SESSION['user_permissions'])) {
                    $_SESSION['user_permissions'] = $newPerms;
                    error_log('Permission reload SUCCESS for role=' . $_SESSION['user_role'] . ' count=' . count($newPerms));
                } else {
                    error_log('Permission reload returned empty for role=' . $_SESSION['user_role'] . ' - preserving existing session permissions count=' . count($_SESSION['user_permissions']));
                }
                break;
            }
        }
        if (!$matched) {
            error_log('Permission reload: no matching role found for role=' . $_SESSION['user_role']);
        }
    } catch (\Throwable $e) {
        error_log('Permission reload FAILED for role=' . ($_SESSION['user_role'] ?? 'none') . ': ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        if (!isset($_SESSION['user_permissions'])) {
            $_SESSION['user_permissions'] = [];
        }
    }
}

/*
|--------------------------------------------------------------------------
| BASE URL
|--------------------------------------------------------------------------
*/

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir, '/'));

/*
|--------------------------------------------------------------------------
| APP URL
|--------------------------------------------------------------------------
*/

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    ? 'https'
    : 'http';

define(
    'APP_URL',
    $protocol . '://' . $_SERVER['HTTP_HOST'] . BASE_URL
);

function loadDotEnv(string $filePath): void
{
    if (!is_file($filePath) || !is_readable($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        if (!str_contains($line, '=')) {
            continue;
        }

        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (($value[0] ?? '') === '"' && str_ends_with($value, '"')) {
            $value = substr($value, 1, -1);
        } elseif (($value[0] ?? '') === "'" && str_ends_with($value, "'")) {
            $value = substr($value, 1, -1);
        }

        putenv("$name=$value");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

loadDotEnv(BASE_PATH . '/.env');

/*
|--------------------------------------------------------------------------
| GOOGLE OAUTH CONFIG
|--------------------------------------------------------------------------
*/

define('GOOGLE_CLIENT_ID', getenv('GOOGLE_CLIENT_ID') ?: '');
define('GOOGLE_CLIENT_SECRET', getenv('GOOGLE_CLIENT_SECRET') ?: '');
define('GOOGLE_REDIRECT_URI', getenv('GOOGLE_REDIRECT_URI') ?: APP_URL . '/auth/google/callback');

/*
|--------------------------------------------------------------------------
| CLEAN REQUEST URI (🔥 IMPORTANT FIX)
|--------------------------------------------------------------------------
*/

// Remove BASE_URL from REQUEST_URI
$requestUri = $_SERVER['REQUEST_URI'];

$baseUrl = BASE_URL;

if ($baseUrl && str_starts_with($requestUri, $baseUrl)) {
    $requestUri = substr($requestUri, strlen($baseUrl));
}

// Remove query string
$requestUri = parse_url($requestUri, PHP_URL_PATH);

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

$router = require BASE_PATH . '/App/Routes/web.php';

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    $requestUri
);

/*
|--------------------------------------------------------------------------
| Redirect Helper
|--------------------------------------------------------------------------
*/

function redirect(string $path): never
{
    header('Location: ' . BASE_URL . $path);
    exit;
}

function can(string $permission): bool
{
    return in_array($permission, $_SESSION['user_permissions'] ?? [], true);
}

function notify(int $recipientId, string $recipientRole, string $message, string $type, ?string $link = null): void
{
    static $service = null;
    if ($service === null) {
        $conn = (new \App\Shared\Infrastructure\Database\Database())->getConnection();
        $service = new \App\Application\NotificationManagement\NotificationService(
            new \App\Infrastructure\Persistence\Repositories\NotificationRepository($conn),
            $conn
        );
    }
    $service->notify($recipientId, $recipientRole, $message, $type, $link);
}

function notifyAllAdmins(string $message, string $type, ?string $link = null): void
{
    static $service = null;
    if ($service === null) {
        $conn = (new \App\Shared\Infrastructure\Database\Database())->getConnection();
        $service = new \App\Application\NotificationManagement\NotificationService(
            new \App\Infrastructure\Persistence\Repositories\NotificationRepository($conn),
            $conn
        );
    }
    $service->notifyAllAdmins($message, $type, $link);
}

function notifyAllByRole(string $role, string $message, string $type, ?string $link = null): void
{
    static $service = null;
    if ($service === null) {
        $conn = (new \App\Shared\Infrastructure\Database\Database())->getConnection();
        $service = new \App\Application\NotificationManagement\NotificationService(
            new \App\Infrastructure\Persistence\Repositories\NotificationRepository($conn),
            $conn
        );
    }
    $service->notifyAllByRole($role, $message, $type, $link);
}