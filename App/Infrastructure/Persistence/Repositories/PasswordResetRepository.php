<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\UserManagement\Repositories\PasswordResetRepositoryInterface;
use PDO;
use App\Domain\UserManagement\Entities\PasswordReset;

final class PasswordResetRepository implements PasswordResetRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
}
