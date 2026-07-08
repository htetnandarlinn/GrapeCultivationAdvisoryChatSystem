<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['master_data', 'questions'];
    foreach ($tables as $table) {
        echo "TABLE: {$table}\n";
        $stmt = $pdo->query("SHOW COLUMNS FROM {$table}");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo $col['Field'] . ': ' . $col['Type'] . "\n";
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
