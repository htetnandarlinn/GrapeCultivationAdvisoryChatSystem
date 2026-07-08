<?php

namespace App\Infrastructure\Persistence\Repositories;

use PDO;
use App\Domain\UserManagement\Entities\PasswordReset;
use App\Shared\Infrastructure\Database\Database;

final class PasswordResetRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        // Ensure the password_resets table exists to avoid runtime errors
        $this->ensureTableExists();
    }

    public function save(PasswordReset $reset): void
    {
        $sql = "
            INSERT INTO password_resets
            (user_id, token, expires_at, created_at)
            VALUES
            (:user_id, :token, :expires_at, :created_at)
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => $reset->getUserId(),
            ':token' => $reset->getToken(),
            ':expires_at' => $reset->getExpiresAt()->format('Y-m-d H:i:s'),
            ':created_at' => $reset->getCreatedAt()->format('Y-m-d H:i:s')
        ]);
    }

    public function findByToken(string $token): ?PasswordReset
    {
        $sql = "SELECT * FROM password_resets WHERE token = :token LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':token' => trim($token)]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new PasswordReset(
            (int)$row['id'],
            (int)$row['user_id'],
            $row['token'],
            new \DateTimeImmutable($row['expires_at']),
            new \DateTimeImmutable($row['created_at'])
        );
    }

    public function findLatestByUserId(int $userId): ?PasswordReset
    {
        $sql = "SELECT * FROM password_resets WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new PasswordReset(
            (int)$row['id'],
            (int)$row['user_id'],
            $row['token'],
            new \DateTimeImmutable($row['expires_at']),
            new \DateTimeImmutable($row['created_at'])
        );
    }

    public function deleteByUserId(int $userId): void
    {
        $sql = "DELETE FROM password_resets WHERE user_id = :user_id";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
    }

    private function ensureTableExists(): void
    {
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
            $this->connection->exec($sql);
        } catch (\Throwable $e) {
            // Log and continue; other DB operations will surface errors if unsupported
            error_log('Could not ensure password_resets table exists: ' . $e->getMessage());
        }
    }
}
