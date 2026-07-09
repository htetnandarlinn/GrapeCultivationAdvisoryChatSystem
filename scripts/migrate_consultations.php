<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new \App\Shared\Infrastructure\Database\Database();
$conn = $db->getConnection();

$conn->exec("
    CREATE TABLE IF NOT EXISTS consultations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        farmer_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        expert_id INT DEFAULT NULL,
        rejection_note TEXT DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT NULL,
        INDEX idx_farmer_id (farmer_id),
        INDEX idx_expert_id (expert_id),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

echo "consultations table created successfully.\n";
