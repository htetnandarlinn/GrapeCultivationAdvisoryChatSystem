<?php

namespace App\Infrastructure\Persistence\Repositories;

use PDO;
use App\Domain\UserManagement\Entities\EmailVerification;
use App\Domain\UserManagement\Repositories\EmailVerificationRepositoryInterface;
use App\Shared\Infrastructure\Database\Database;

final class EmailVerificationRepository implements EmailVerificationRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->connection->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            true
        );
    }

    /**
     * Save verification token
     */
    public function save(EmailVerification $verification): void
    {
        $sql = "
            INSERT INTO email_verifications
            (
                user_id,
                token,
                expires_at,
                verified_at,
                created_at
            )
            VALUES
            (
                :user_id,
                :token,
                :expires_at,
                :verified_at,
                :created_at
            )
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => $verification->getUserId(),
            ':token' => $verification->getToken(),
            ':expires_at' => $verification
                ->getExpiresAt()
                ->format('Y-m-d H:i:s'),
            ':verified_at' => $verification->getVerifiedAt()
                ? $verification->getVerifiedAt()->format('Y-m-d H:i:s')
                : null,
            ':created_at' => $verification
                ->getCreatedAt()
                ->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Update verification status
     */
    public function update(
        EmailVerification $verification
    ): void {

        $sql = "
            UPDATE email_verifications
            SET
                verified_at = :verified_at
            WHERE
                id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':id' => $verification->getId(),
            ':verified_at' => $verification->getVerifiedAt()
                ? $verification->getVerifiedAt()->format('Y-m-d H:i:s')
                : null,
        ]);
    }

    /**
     * Find by token
     */
public function findByToken(string $token): ?EmailVerification
{
    $sql = "
        SELECT *
        FROM email_verifications
        WHERE token = :token
        LIMIT 1
    ";

    $stmt = $this->connection->prepare($sql);

    $stmt->execute([
        ':token' => trim($token)
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        return null;
    }

    return $this->mapToEntity($row);
}
    /**
     * Find by user id
     */
    public function findByUserId(
        int $userId
    ): ?EmailVerification {

        $sql = "
            SELECT *
            FROM email_verifications
            WHERE user_id = :user_id
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->mapToEntity($row);
    }

    /**
     * Delete by user id
     */
    public function deleteByUserId(
        int $userId
    ): void {

        $sql = "
            DELETE FROM email_verifications
            WHERE user_id = :user_id
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId
        ]);
    }

    /**
     * Convert database row to Entity
     */
    private function mapToEntity(
        array $row
    ): EmailVerification {

        return new EmailVerification(
            id: (int)$row['id'],
            userId: (int)$row['user_id'],
            token: $row['token'],
            expiresAt: new \DateTimeImmutable(
                $row['expires_at']
            ),
            verifiedAt: !empty($row['verified_at'])
                ? new \DateTimeImmutable(
                    $row['verified_at']
                )
                : null,
            createdAt: new \DateTimeImmutable(
                $row['created_at']
            )
        );
    }
}   