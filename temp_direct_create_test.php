<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=grapecultivation01;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Trying direct CREATE TABLE...\n";
    $sql = "CREATE TABLE users (user_id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(100) NOT NULL UNIQUE, email VARCHAR(255) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, profile_image VARCHAR(255) DEFAULT NULL, user_type_id INT NOT NULL DEFAULT 1, status_id INT NOT NULL DEFAULT 21, is_active TINYINT(1) NOT NULL DEFAULT 1, is_verified TINYINT(1) NOT NULL DEFAULT 0, is_login TINYINT(1) NOT NULL DEFAULT 0, verification_token VARCHAR(255) DEFAULT NULL, verification_token_expire_at DATETIME DEFAULT NULL, email_verified_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, INDEX idx_user_type_id (user_type_id), INDEX idx_status_id (status_id), INDEX idx_email (email), INDEX idx_username (username)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    try {
        $pdo->exec($sql);
        echo "CREATE TABLE succeeded\n";
    } catch (Throwable $e) {
        echo 'CREATE ERROR: ' . $e->getMessage() . "\n";
        try {
            $status = $pdo->query('SHOW ENGINE INNODB STATUS')->fetch(PDO::FETCH_ASSOC);
            echo "ENGINE STATUS:\n";
            var_dump($status);
        } catch (Throwable $e2) {
            echo 'ENGINE STATUS ERROR: ' . $e2->getMessage() . "\n";
        }
    }
    echo "Checking TABLES LIKE 'users'...\n";
    $rows = $pdo->query("SHOW TABLES LIKE 'users'")->fetchAll(PDO::FETCH_ASSOC);
    var_dump($rows);
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
