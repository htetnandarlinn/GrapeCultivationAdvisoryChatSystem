<?php

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

session_start();

/*
|--------------------------------------------------------------------------
| RELOAD PERMISSIONS (so assigned permissions take effect immediately)
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] !== 'admin' && isset($_SESSION['user'])) {
    try {
        $permDb = new \App\Shared\Infrastructure\Database\Database();
        $permConn = $permDb->getConnection();
        $stmt = $permConn->prepare('
            SELECT utp.permission_key
            FROM user_type_permissions utp
            JOIN master_data md ON md.id = utp.user_type_id
            WHERE md.code = :role AND md.category = \'USER_TYPE\'
        ');
        $stmt->execute([':role' => $_SESSION['user_role']]);
        $_SESSION['user_permissions'] = array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'permission_key');
    } catch (\Throwable) {
        $_SESSION['user_permissions'] = [];
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
    if (($_SESSION['user_role'] ?? '') === 'admin') {
        return true;
    }

    return in_array($permission, $_SESSION['user_permissions'] ?? [], true);
}