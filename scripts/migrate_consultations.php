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
        paid_at DATETIME DEFAULT NULL,
        expires_at DATETIME DEFAULT NULL,
        idempotency_key VARCHAR(64) DEFAULT NULL,
        payment_method VARCHAR(50) DEFAULT NULL,
        transaction_image VARCHAR(255) DEFAULT NULL,
        consultation_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        verified_by VARCHAR(100) DEFAULT NULL,
        verified_at DATETIME DEFAULT NULL,
        refund_status VARCHAR(20) DEFAULT NULL,
        refund_date DATETIME DEFAULT NULL,
        refund_amount DECIMAL(10,2) DEFAULT NULL,
        admin_notes TEXT DEFAULT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT NULL,
        INDEX idx_farmer_id (farmer_id),
        INDEX idx_expert_id (expert_id),
        INDEX idx_status (status),
        INDEX idx_expires_at (expires_at),
        UNIQUE KEY uk_idempotency_key (idempotency_key)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

$conn->exec("
    CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        consultation_id INT NOT NULL,
        farmer_id INT NOT NULL,
        amount DECIMAL(10,2) NOT NULL,
        payment_date DATETIME NOT NULL,
        start_date DATE NOT NULL,
        expiry_date DATE NOT NULL,
        payment_status VARCHAR(20) NOT NULL DEFAULT 'PENDING',
        transaction_reference VARCHAR(100) DEFAULT NULL,
        payment_method VARCHAR(20) DEFAULT NULL,
        transaction_image VARCHAR(255) DEFAULT NULL,
        verified_by VARCHAR(100) DEFAULT NULL,
        verified_at DATETIME DEFAULT NULL,
        refund_status VARCHAR(20) DEFAULT NULL,
        refund_date DATETIME DEFAULT NULL,
        refund_amount DECIMAL(10,2) DEFAULT NULL,
        admin_notes TEXT DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_consultation_id (consultation_id),
        INDEX idx_farmer_id (farmer_id),
        INDEX idx_payment_status (payment_status),
        INDEX idx_refund_status (refund_status),
        FOREIGN KEY (consultation_id) REFERENCES consultations(id) ON DELETE CASCADE,
        FOREIGN KEY (farmer_id) REFERENCES users(user_id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");

echo "consultations table created successfully.\n";
