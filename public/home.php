<?php

if (!defined('BASE_URL')) {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    define('BASE_URL', rtrim($scriptDir, '/') === '/' ? '' : rtrim($scriptDir, '/'));
}

$baseUrl = BASE_URL === '/' ? '' : BASE_URL;

include __DIR__ . '/../App/Presentation/Views/home.php';
