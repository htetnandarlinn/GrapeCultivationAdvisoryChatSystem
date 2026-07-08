<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['master_data', 'users'];
    foreach ($tables as $table) {
        echo "TABLE: {$table}\n";
        $stmt = $pdo->query("SELECT * FROM {$table} LIMIT 5");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            echo json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
