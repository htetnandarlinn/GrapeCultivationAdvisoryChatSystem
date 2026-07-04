<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Shared\Infrastructure\Database\Database;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    /* ===================== SAVE ===================== */

    public function save(User $user): void
    {
       $sql = "
INSERT INTO users (
    username,
    email,
    password,
    phone,
    address,
    profile_image,
    user_type_id,
    status_id,
    is_active,
    is_verified,
    is_login,
    verification_token,
    verification_token_expire_at,
    email_verified_at,
    created_at,
    updated_at
)
VALUES (
    :username,
    :email,
    :password,
    :phone,
    :address,
    :profile_image,
    :user_type_id,
    :status_id,
    :is_active,
    :is_verified,
    :is_login,
    :verification_token,
    :verification_token_expire_at,
    :email_verified_at,
    NOW(),
    NOW()
)";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
    ':username' => $user->getUsername(),
    ':email' => $user->getEmail()->getValue(),
    ':password' => $user->getPasswordHash(),
    ':phone' => $user->getPhoneNumber(),
    ':address' => $user->getAddress(),
    ':profile_image' => $user->getProfileImage(),
    ':user_type_id' => $this->mapUserTypeToId($user->getType()),
    ':status_id' => $this->mapUserStatusToId($user->getStatus()),

    ':is_active' => $user->isActive() ? 1 : 0,
    ':is_verified' => $user->isVerified() ? 1 : 0,
    ':is_login' => $user->isLogin() ? 1 : 0,

    ':verification_token' => $user->getVerificationToken(),
    ':verification_token_expire_at' => $this->formatMyanmarDateTime($user->getVerificationTokenExpireAt()),
    ':email_verified_at' => $this->formatMyanmarDateTime($user->getEmailVerifiedAt()),
]);

        $user->setId((int) $this->connection->lastInsertId());
    }

    /* ===================== UPDATE ===================== */

    public function update(User $user): void
    {
       $sql = "
UPDATE users
SET
    username = :username,
    email = :email,
    password = :password,
    phone = :phone,
    address = :address,
    profile_image = :profile_image,
    user_type_id = :user_type_id,
    status_id = :status_id,
    is_active = :is_active,
    is_verified = :is_verified,
    is_login = :is_login,
    verification_token = :verification_token,
    verification_token_expire_at = :verification_token_expire_at,
    email_verified_at = :email_verified_at,
    updated_at = NOW()
WHERE user_id = :id
";

        $stmt = $this->connection->prepare($sql);

        $profileImage = $user->getProfileImage();

        $stmt->execute([
            ':username' => $user->getUsername(),
            ':email' => $user->getEmail()->getValue(),
            ':password' => $user->getPasswordHash(),
            ':phone' => $user->getPhoneNumber(),
            ':address' => $user->getAddress(),
            ':profile_image' => ($profileImage === null || $profileImage === '') ? null : $profileImage,
            ':user_type_id' => $this->mapUserTypeToId($user->getType()),
            ':status_id' => $this->mapUserStatusToId($user->getStatus()),
            ':is_active' => $user->isActive() ? 1 : 0,
            ':is_verified' => $user->isVerified() ? 1 : 0,
            ':is_login' => $user->isLogin() ? 1 : 0,
            ':verification_token' => $user->getVerificationToken(),
            ':verification_token_expire_at' => $this->formatMyanmarDateTime($user->getVerificationTokenExpireAt()),
            ':email_verified_at' => $this->formatMyanmarDateTime($user->getEmailVerifiedAt()),
            ':id' => $user->getId(),
        ]);
    }

    public function updateStatus(
        int $userId,
        string $status
    ): void {
        $sql = '
        UPDATE users
        SET
            status_id = :status_id,
            updated_at = NOW()
        WHERE
            user_id = :user_id
    ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':user_id' => $userId,
            ':status_id' => $this->mapUserStatusToId($status),
        ]);
    }

    /* ===================== FIND BY ID ===================== */

    public function findById(int $id): ?User
    {
        $sql = '
            SELECT *
            FROM users
            WHERE user_id = :id
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /* ===================== FIND BY VERIFICATION TOKEN ===================== */

    public function findByVerificationToken(string $token): ?User
    {
        $sql = '
            SELECT *
            FROM users
            WHERE verification_token = :token
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':token' => $token]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /* ===================== FIND BY EMAIL ===================== */

    public function findByEmail(string $email): ?User
    {
        $sql = '
            SELECT *
            FROM users
            WHERE email = :email
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /* ===================== FIND BY USERNAME ===================== */

    public function findByUsername(string $username): ?User
    {
        $sql = '
            SELECT *
            FROM users
            WHERE username = :username
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':username' => $username]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /* ===================== FIND BY USERNAME OR EMAIL ===================== */

    public function findByUsernameOrEmail(string $identifier): ?User
    {
        $identifier = trim((string) $identifier);

        $sql = '
            SELECT *
            FROM users
            WHERE (username = :identifier OR email = :identifier)
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':identifier' => $identifier
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /* ===================== EXISTS ===================== */

    public function emailExists(string $email): bool
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE email = :email
              AND deleted_at IS NULL
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':email' => $email]);

        return (int) $stmt->fetchColumn() > 0;
    }

    /* ===================== ENTITY MAPPER ===================== */

    private function mapToEntity(array $row): User
    {
        $typeId = (int) ($row['user_type_id'] ?? 1);
        $statusId = (int) ($row['status_id'] ?? 1);

        $userType = match ($typeId) {
            2 => UserType::expert(),
            3 => UserType::admin(),
            default => UserType::farmer(),
        };

        $status = match ($statusId) {
            4 => UserStatus::active(),
            5 => UserStatus::inactive(),
            6 => UserStatus::blocked(),
            21 => UserStatus::pending(),
            default => throw new \RuntimeException(
                "Unknown status_id: {$statusId}"
            ),
        };

        return new User(
            id: (int) $row['user_id'],
            username: $row['username'],
            email: new Email($row['email']),
            phoneNumber: $row['phone'] ?? '',
            address: $row['address'] ?? '',
            passwordHash: $row['password'],
            type: $userType,
            status: $status,
            isVerified: (bool) ($row['is_verified'] ?? 0),
            isLogin: (bool) ($row['is_login'] ?? 0),
            profileImage: $row['profile_image'] ?? null,
            verificationToken: $row['verification_token'] ?? null,
            verificationTokenExpireAt: !empty($row['verification_token_expire_at'])
                ? new \DateTimeImmutable($row['verification_token_expire_at'])
                : null,
            emailVerifiedAt: !empty($row['email_verified_at'])
                ? new \DateTimeImmutable($row['email_verified_at'])
                : null,
            createdAt: isset($row['created_at'])
                ? new \DateTimeImmutable($row['created_at'])
                : null,
            updatedAt: isset($row['updated_at'])
                ? new \DateTimeImmutable($row['updated_at'])
                : null,
            deletedAt: !empty($row['deleted_at'])
                ? new \DateTimeImmutable($row['deleted_at'])
                : null
        );
    }

    /* ===================== MAPPERS ===================== */

    private function formatMyanmarDateTime(?\DateTimeImmutable $dateTime): ?string
    {
        if ($dateTime === null) {
            return null;
        }

        return $dateTime->setTimezone(new \DateTimeZone('Asia/Yangon'))->format('Y-m-d H:i:s');
    }

    private function mapUserTypeToId($userType): int
    {
        $value = is_object($userType) ? $userType->getValue() : $userType;

        return match (strtolower($value)) {
            'expert' => 2,
            'admin' => 3,
            default => 1,
        };
    }

    private function mapUserStatusToId($status): int
    {
        $value = is_object($status)
            ? strtolower($status->getValue())
            : strtolower((string) $status);

        try {
            $sql = 'SELECT master_data_id FROM master_data WHERE category = :category AND name = :name LIMIT 1';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':category' => 'status',
                ':name' => $value,
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row !== false && isset($row['master_data_id'])) {
                return (int) $row['master_data_id'];
            }
        } catch (\Throwable $e) {
            // Fall back to the legacy hard-coded mapping if master_data is unavailable.
        }

        return match ($value) {
            'active' => 4,
            'inactive' => 5,
            'blocked' => 6,
            'pending' => 21,
            default => throw new \InvalidArgumentException(
                "Unknown user status: {$value}"
            ),
        };
    }
}
