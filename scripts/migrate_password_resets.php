<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Shared\Infrastructure\Database\Database;

$pdo = (new Database())->getConnection();

$sql = "
CREATE TABLE IF NOT EXISTS password_resets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  token VARCHAR(128) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX (token(64)),
  INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $pdo->exec($sql);
    echo "password_resets table created or already exists.\n";
} catch (Exception $e) {
    echo 'Migration failed: ' . $e->getMessage() . "\n";
    exit(1);
}

echo "Done.\n";
