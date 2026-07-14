<?php

require_once __DIR__ . '/../vendor/autoload.php';

$db = new \App\Shared\Infrastructure\Database\Database();
$conn = $db->getConnection();

$conn->exec("ALTER TABLE consultations
    ADD COLUMN IF NOT EXISTS paid_at DATETIME DEFAULT NULL AFTER rejection_note,
    ADD COLUMN IF NOT EXISTS expires_at DATETIME DEFAULT NULL AFTER paid_at,
    ADD COLUMN IF NOT EXISTS idempotency_key VARCHAR(64) DEFAULT NULL AFTER expires_at,
    ADD INDEX IF NOT EXISTS idx_status (status),
    ADD INDEX IF NOT EXISTS idx_expires_at (expires_at)
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
