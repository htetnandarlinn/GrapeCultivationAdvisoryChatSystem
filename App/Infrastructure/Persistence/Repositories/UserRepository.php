<?php

namespace App\Infrastructure\Persistence\Repositories;

use PDO;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Shared\Infrastructure\Database\Database;

final class UserRepository implements UserRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    /**
     * Save new user
     */
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
        created_at,
        updated_at
    ) VALUES (
        :username,
        :email,
        :password,
        :phone,
        :address,
        :profile_image,
        :user_type_id,
        :status_id,
        NOW(),
        NOW()
    )
";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
           'username' => $user->getUsername(),
            'email'         => $user->getEmail()->getValue(),
            'password'      => $user->getPasswordHash(),
            'phone'         => $user->getPhoneNumber(),
            'address'       => $user->getAddress(),
            'profile_image' => $user->getProfileImage(),
            'user_type_id'  => $this->mapUserTypeToId($user->getType()),
            'status_id'     => $this->mapUserStatusToId($user->getStatus()),
        ]);

        $user->setId((int) $this->connection->lastInsertId());
    }

  
    /**
     * Find user by ID (excluding deleted users)
     */
    public function findById(int $id): ?User
    {
        $sql = "
            SELECT *
            FROM users
            WHERE user_id = :id
              AND deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /**
     * Find user by email
     */
    public function findByEmail(string $email): ?User
    {
        $sql = "
            SELECT *
            FROM users
            WHERE email = :email
              AND deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    /**
     * Check if username exists
     */
    public function findByUsername(string $username): ?User
{
    $sql = "
        SELECT *
        FROM users
        WHERE username = :username
          AND deleted_at IS NULL
        LIMIT 1
    ";

    $stmt = $this->connection->prepare($sql);

    $stmt->execute([
        'username' => $username
    ]);

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row ? $this->mapToEntity($row) : null;
}

    public function findByUsernameOrEmail(string $identifier): ?User
    {
        $sql = "
            SELECT *
            FROM users
            WHERE (username = :identifier OR email = :identifier)
              AND deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['identifier' => $identifier]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    public function emailExists(string $email): bool
    {
        $sql = "
            SELECT COUNT(*) 
            FROM users
            WHERE email = :email
              AND deleted_at IS NULL
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['email' => $email]);

        return (int) $stmt->fetchColumn() > 0;
    }

 /**
 * Convert database row to User entity.
 */
private function mapToEntity(array $row): User
{
    $typeId = (int)($row['user_type_id'] ?? 1);
    $statusId = (int)($row['status_id'] ?? 1);

    $userType = match ($typeId) {
        2 => UserType::expert(),
        3 => UserType::admin(),
        default => UserType::farmer(),
    };

    $status = match ($statusId) {
        2 => UserStatus::blocked(),
        default => UserStatus::active(),
    };

    return new User(
        id: (int)$row['user_id'],
        username: $row['username'],
       email: new Email($row['email']),
        phoneNumber: $row['phone'] ?? '',
        address: $row['address'] ?? '',
        passwordHash: $row['password'],
        type: $userType,
        status: $status,
        profileImage: $row['profile_image'] ?? null,
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

    /**
     * Map UserType value object to database id.
     */
    private function mapUserTypeToId($userType): int
    {
        if (is_string($userType)) {
            $value = $userType;
        } elseif (is_object($userType) && method_exists($userType, 'getValue')) {
            $value = $userType->getValue();
        } else {
            $value = '';
        }

        return match (strtolower((string) $value)) {
            'expert' => 2,
            'admin' => 3,
            default => 1,
        };
    }

    /**
     * Map UserStatus value object to database id.
     */
    private function mapUserStatusToId($status): int
    {
        if (is_string($status)) {
            $value = $status;
        } elseif (is_object($status) && method_exists($status, 'getValue')) {
            $value = $status->getValue();
        } else {
            $value = '';
        }

        return strtolower((string) $value) === 'blocked' ? 2 : 1;
    }

}