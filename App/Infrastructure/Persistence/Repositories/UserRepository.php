<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;
use PDO;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    public function save(User $user): void
    {
        $sql = '
INSERT INTO users (
    username,
    email,
    google_id,
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
    :google_id,
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
            ':google_id' => $user->getGoogleId(),
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

    public function update(User $user): void
    {
        $sql = '
UPDATE users
SET
    username = :username,
    email = :email,
    google_id = :google_id,
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
            ':google_id' => $user->getGoogleId(),
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

    public function updateStatus(int $userId, string $status): void
    {
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

    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->connection->prepare(
            "SELECT * FROM users WHERE user_id IN ($placeholders) AND deleted_at IS NULL"
        );
        $stmt->execute(array_values($ids));
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];
        foreach ($rows as $row) {
            $users[(int) $row['user_id']] = $this->mapToEntity($row);
        }

        return $users;
    }

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

    public function findByGoogleId(string $googleId): ?User
    {
        $sql = '
            SELECT *
            FROM users
            WHERE google_id = :google_id
              AND deleted_at IS NULL
            LIMIT 1
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':google_id' => $googleId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

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
            googleId: $row['google_id'] ?? null,
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
