<?php
require_once __DIR__ . '/../vendor/autoload.php';

$db = new App\Shared\Infrastructure\Database\Database();
$conn = $db->getConnection();

$conn->exec("
    ALTER TABLE users
    ADD COLUMN google_id VARCHAR(255) NULL DEFAULT NULL AFTER email,
    ADD UNIQUE INDEX idx_google_id (google_id)
");

echo "Migration successful: google_id column added to users table.\n";