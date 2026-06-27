<?php
// Redirect requests from project root to the public directory preserving path and query.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$parsed = parse_url($requestUri);
$path = $parsed['path'] ?? '/';
$query = isset($parsed['query']) ? ('?' . $parsed['query']) : '';

// If the request already targets the public folder, serve the public front controller.
if (strpos($path, '/public') === 0 || strpos($path, '/public/') === 0) {
    require __DIR__ . '/public/index.php';
    exit;
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$projectBase = rtrim(dirname($scriptName), '/');

// Compute the path relative to project base to avoid duplicating the base when building the target.
$relative = $path;
if ($projectBase !== '' && $projectBase !== '/' && str_starts_with($path, $projectBase)) {
    $relative = substr($path, strlen($projectBase));
    if ($relative === '') {
        $relative = '/';
    }
}

$target = ($projectBase === '' ? '' : $projectBase) . '/public' . $relative . $query;
$target = preg_replace('#/+#', '/', $target);

header('Location: ' . $target, true, 302);
exit;
