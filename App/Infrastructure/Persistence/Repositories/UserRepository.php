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
        $this->ensureUsersTableExists();
        $this->ensureProfileImageColumn();
    }

    private function ensureUsersTableExists(): void
    {
        try {
            $stmt = $this->connection->query("SHOW TABLES LIKE 'users'");
            $table = $stmt->fetch(PDO::FETCH_NUM);

            if ($table !== false) {
                return;
            }

            $this->createUsersTable('InnoDB');
        } catch (\Throwable $e) {
            if ($this->isRecoverableUsersTableError($e)) {
                $this->cleanupOrphanedUsersTablespace();

                try {
                    $this->createUsersTable('InnoDB');
                } catch (\Throwable $e2) {
                    $this->createUsersTable('MyISAM');
                }

                return;
            }

            throw new \RuntimeException('Unable to ensure users table exists.', 0, $e);
        }
    }

    private function createUsersTable(string $engine = 'InnoDB'): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                user_id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(100) NOT NULL UNIQUE,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(50) DEFAULT NULL,
                address VARCHAR(255) DEFAULT NULL,
                profile_image VARCHAR(255) DEFAULT NULL,
                user_type_id INT NOT NULL DEFAULT 1,
                status_id INT NOT NULL DEFAULT 21,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                is_verified TINYINT(1) NOT NULL DEFAULT 0,
                is_login TINYINT(1) NOT NULL DEFAULT 0,
                verification_token VARCHAR(255) DEFAULT NULL,
                verification_token_expire_at DATETIME DEFAULT NULL,
                email_verified_at DATETIME DEFAULT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT NULL,
                deleted_at DATETIME DEFAULT NULL,
                INDEX idx_user_type_id (user_type_id),
                INDEX idx_status_id (status_id),
                INDEX idx_email (email),
                INDEX idx_username (username)
            ) ENGINE={$engine} DEFAULT CHARSET=utf8mb4;
        ";

        $this->connection->exec($sql);
    }

    private function cleanupOrphanedUsersTablespace(): void
    {
        try {
            $this->connection->exec('DROP TABLE IF EXISTS users');
        } catch (\Throwable) {
            // ignore failure if metadata is inconsistent
        }

        $database = (string) $this->connection->query('SELECT DATABASE()')->fetchColumn();
        $stmt = $this->connection->query("SHOW VARIABLES LIKE 'datadir'");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $dataDir = $row['Value'] ?? $row['value'] ?? null;

        if ($database === '' || $dataDir === null) {
            throw new \RuntimeException('Could not determine database or datadir to discard orphaned users tablespace.');
        }

        $dataDir = rtrim($dataDir, '/\\');
        $path = $dataDir . DIRECTORY_SEPARATOR . $database . DIRECTORY_SEPARATOR . 'users.ibd';
        $frmPath = $dataDir . DIRECTORY_SEPARATOR . $database . DIRECTORY_SEPARATOR . 'users.frm';

        foreach ([$path, $frmPath] as $file) {
            if (file_exists($file)) {
                @unlink($file);
            }
        }
    }

    private function isRecoverableUsersTableError(\Throwable $e): bool
    {
        if ($this->isInnoDBTablespaceError($e)) {
            return true;
        }

        if (str_contains($e->getMessage(), "Can't create table")
            && str_contains($e->getMessage(), 'errno: 168')) {
            return true;
        }

        return false;
    }

    private function isInnoDBTablespaceError(\Throwable $e): bool
    {
        return str_contains($e->getMessage(), 'Tablespace for table')
            && str_contains($e->getMessage(), 'Please DISCARD the tablespace before IMPORT');
    }

    private function ensureProfileImageColumn(): void
    {
        try {
            $stmt = $this->connection->query("SHOW COLUMNS FROM users LIKE 'profile_image'");
            $column = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($column === false) {
                $this->connection->exec("ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL");
            }
        } catch (\Throwable $e) {
            // Ignore schema issues during bootstrap and let the app continue if the table is unavailable.
        }
    }

    /* ===================== SAVE ===================== */

    public function save(User $user): void
    {
        $sql = '
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
)';

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
        $sql = '
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
';

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

    public function findFarmers(): array
    {
        $sql = '
            SELECT *
            FROM users
            WHERE user_type_id = :type_id
              AND deleted_at IS NULL
            ORDER BY created_at DESC
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type_id' => 2]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function countFarmers(): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE user_type_id = :type_id
              AND deleted_at IS NULL
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type_id' => 2]);

        return (int) $stmt->fetchColumn();
    }

    public function findExperts(): array
    {
        $sql = '
            SELECT *
            FROM users
            WHERE user_type_id = :type_id
              AND deleted_at IS NULL
            ORDER BY created_at DESC
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type_id' => 3]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function countExperts(): int
    {
        $sql = '
            SELECT COUNT(*)
            FROM users
            WHERE user_type_id = :type_id
              AND deleted_at IS NULL
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':type_id' => 3]);

        return (int) $stmt->fetchColumn();
    }

   public function deleteById(int $id): void
{
    $sql = "
        DELETE FROM users
        WHERE user_id = :id
    ";

    $stmt = $this->connection->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);
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
            1 => UserType::admin(),
            2 => UserType::farmer(),
            3 => UserType::expert(),
            default => throw new \RuntimeException(
                "Unknown user_type_id: {$typeId}"
            ),
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
        $value = is_object($userType)
            ? strtolower($userType->getValue())
            : strtolower((string) $userType);

        try {
            $stmt = $this->connection->prepare(
                'SELECT id FROM master_data WHERE category = "USER_TYPE" AND (UPPER(code)=UPPER(:value) OR UPPER(label)=UPPER(:value)) LIMIT 1'
            );
            $stmt->execute([':value' => $value]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && isset($row['id'])) {
                return (int) $row['id'];
            }
        } catch (\Throwable $e) {
            // Fall back to the legacy hard-coded mapping if master_data is unavailable.
        }

        return match ($value) {
            'admin' => 1,
            'farmer' => 2,
            'expert' => 3,
            default => throw new \InvalidArgumentException(
                "Unknown user type: {$value}"
            ),
        };
    }


    private function mapUserStatusToId($status): int
    {
        $value = is_object($status)
            ? strtolower($status->getValue())
            : strtolower((string) $status);

        try {
            $sql = '
            SELECT id
            FROM master_data
            WHERE UPPER(category)=UPPER(:category)
            AND UPPER(code)=UPPER(:code)
            LIMIT 1
            ';
            $stmt = $this->connection->prepare($sql);
            $stmt->execute([
                ':category' => 'STATUS',
                ':code' => $value
            ]);

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return (int) $row['id'];
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
