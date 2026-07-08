<?php
require __DIR__ . '/vendor/autoload.php';

try {
    $pdo = new PDO('mysql:host=localhost;dbname=information_schema;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $queries = [
        "SHOW VARIABLES LIKE 'datadir'",
        "SHOW TABLES FROM grapecultivation01 LIKE 'users'",
        "SELECT TABLE_NAME, ENGINE, TABLE_TYPE, TABLE_ROWS FROM TABLES WHERE TABLE_SCHEMA='grapecultivation01' AND TABLE_NAME='users'",
        "SELECT * FROM INNODB_SYS_TABLES WHERE NAME LIKE 'grapecultivation01/%'",
        "SELECT * FROM INNODB_SYS_TABLESPACES WHERE NAME LIKE '%users%'"
    ];

    foreach ($queries as $sql) {
        echo "SQL: $sql\n";
        try {
            $stmt = $pdo->query($sql);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            var_dump($rows);
        } catch (Throwable $e) {
            echo 'ERR: ' . $e->getMessage() . PHP_EOL;
        }
        echo "\n";
    }
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . PHP_EOL;
}
