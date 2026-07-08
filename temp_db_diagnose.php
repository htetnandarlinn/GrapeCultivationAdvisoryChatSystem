<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    var_dump($rows);
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
