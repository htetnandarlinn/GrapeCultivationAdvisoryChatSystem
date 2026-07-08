<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\PermissionManagement\Entities\Permission;
use App\Domain\PermissionManagement\Repositories\PermissionRepositoryInterface;
use PDO;

final class PermissionRepository implements PermissionRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT permission_id, permission_key, permission_name, COALESCE(description, \'\') AS description FROM permissions ORDER BY permission_id ASC');
        return array_map(fn(array $row): Permission => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function existsByKey(string $key): bool
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM permissions WHERE permission_key = :key');
        $stmt->execute([':key' => $key]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $key, string $name, string $description = ''): int
    {
        $stmt = $this->connection->prepare('INSERT INTO permissions (permission_key, permission_name, description) VALUES (:key, :name, :description)');
        $stmt->execute([
            ':key' => $key,
            ':name' => $name,
            ':description' => $description,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function findPermissionsByUserTypeId(int $userTypeId): array
    {
        $stmt = $this->connection->prepare('
            SELECT p.permission_id, p.permission_key, p.permission_name, COALESCE(p.description, \'\') AS description
            FROM permissions p
            JOIN user_type_permissions utp ON p.permission_id = utp.permission_id
            WHERE utp.user_type_id = :user_type_id
            ORDER BY p.permission_id ASC
        ');
        $stmt->execute([':user_type_id' => $userTypeId]);
        return array_map(fn(array $row): Permission => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function assignPermissionsToUserType(int $userTypeId, array $permissionIds): void
    {
        $this->connection->beginTransaction();
        try {
            $delete = $this->connection->prepare('DELETE FROM user_type_permissions WHERE user_type_id = :user_type_id');
            $delete->execute([':user_type_id' => $userTypeId]);

            if (!empty($permissionIds)) {
                $insert = $this->connection->prepare('INSERT INTO user_type_permissions (user_type_id, permission_id, permission_key) VALUES (:user_type_id, :permission_id, :permission_key)');
                $keyStmt = $this->connection->prepare('SELECT permission_key FROM permissions WHERE permission_id = :id');

                foreach ($permissionIds as $pid) {
                    $keyStmt->execute([':id' => $pid]);
                    $key = $keyStmt->fetchColumn();

                    if ($key !== false && $key !== null) {
                        $insert->execute([
                            ':user_type_id' => $userTypeId,
                            ':permission_id' => $pid,
                            ':permission_key' => $key,
                        ]);
                    }
                }
            }

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }

    public function getAssignedPermissionIds(int $userTypeId): array
    {
        $stmt = $this->connection->prepare('SELECT permission_id FROM user_type_permissions WHERE user_type_id = :user_type_id');
        $stmt->execute([':user_type_id' => $userTypeId]);
        return array_map(fn(array $row): int => (int) $row['permission_id'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function toEntity(array $row): Permission
    {
        return new Permission(
            id: (int) $row['permission_id'],
            key: (string) $row['permission_key'],
            name: (string) $row['permission_name'],
            description: (string) ($row['description'] ?? ''),
        );
    }
}
