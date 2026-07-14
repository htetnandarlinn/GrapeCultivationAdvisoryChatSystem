<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new \App\Shared\Infrastructure\Database\Database();
$conn = $db->getConnection();

$conn->exec("ALTER TABLE consultations
    ADD COLUMN IF NOT EXISTS paid_at DATETIME DEFAULT NULL AFTER rejection_note,
    ADD COLUMN IF NOT EXISTS expires_at DATETIME DEFAULT NULL AFTER paid_at,
    ADD COLUMN IF NOT EXISTS idempotency_key VARCHAR(64) DEFAULT NULL AFTER expires_at,
    ADD COLUMN IF NOT EXISTS payment_method VARCHAR(20) DEFAULT NULL AFTER idempotency_key,
    ADD COLUMN IF NOT EXISTS transaction_image VARCHAR(255) DEFAULT NULL AFTER payment_method,
    ADD COLUMN IF NOT EXISTS consultation_fee DECIMAL(10,2) DEFAULT 0.00 AFTER transaction_image,
    ADD COLUMN IF NOT EXISTS verified_by VARCHAR(100) DEFAULT NULL AFTER consultation_fee,
    ADD COLUMN IF NOT EXISTS verified_at DATETIME DEFAULT NULL AFTER verified_by,
    ADD COLUMN IF NOT EXISTS refund_status VARCHAR(20) DEFAULT NULL AFTER verified_at,
    ADD COLUMN IF NOT EXISTS refund_date DATETIME DEFAULT NULL AFTER refund_status,
    ADD COLUMN IF NOT EXISTS refund_amount DECIMAL(10,2) DEFAULT NULL AFTER refund_date,
    ADD COLUMN IF NOT EXISTS admin_notes TEXT DEFAULT NULL AFTER refund_amount,
    ADD INDEX IF NOT EXISTS idx_status (status),
    ADD INDEX IF NOT EXISTS idx_expires_at (expires_at),
    ADD INDEX IF NOT EXISTS idx_refund_status (refund_status)
");

try {
    $conn->exec("ALTER TABLE consultations ADD UNIQUE INDEX uk_idempotency_key (idempotency_key)");
} catch (\PDOException $e) {
    if (str_contains($e->getMessage(), 'Duplicate key name')) {
        // index already exists, ignore
    } else {
        throw $e;
    }
}

echo "Consultations table updated with payment columns successfully.\n";
