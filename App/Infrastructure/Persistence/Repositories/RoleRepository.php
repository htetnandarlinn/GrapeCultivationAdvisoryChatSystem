<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\RoleManagement\Entities\Role;
use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;
use PDO;

final class RoleRepository implements RoleRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function findAll(): array
    {
        $stmt = $this->connection->prepare('SELECT id, code, label FROM master_data WHERE category = :category ORDER BY id ASC');
        $stmt->execute([':category' => 'USER_TYPE']);
        return array_map(fn(array $row): Role => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findById(int $id): ?Role
    {
        $stmt = $this->connection->prepare('SELECT id, code, label FROM master_data WHERE id = :id AND category = :category LIMIT 1');
        $stmt->execute([':id' => $id, ':category' => 'USER_TYPE']);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->toEntity($row) : null;
    }

    public function existsByCode(string $code, ?int $excludeId = null): bool
    {
        $sql = 'SELECT COUNT(*) FROM master_data WHERE code = :code AND category = :category';
        $params = [':code' => $code, ':category' => 'USER_TYPE'];

        if ($excludeId !== null) {
            $sql .= ' AND id != :exclude_id';
            $params[':exclude_id'] = $excludeId;
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function create(string $code, string $label): int
    {
        $stmt = $this->connection->prepare('INSERT INTO master_data (category, code, label) VALUES (:category, :code, :label)');
        $stmt->execute([
            ':category' => 'USER_TYPE',
            ':code' => $code,
            ':label' => $label,
        ]);
        return (int) $this->connection->lastInsertId();
    }

    public function update(int $id, string $code, string $label): void
    {
        $stmt = $this->connection->prepare('UPDATE master_data SET code = :code, label = :label WHERE id = :id');
        $stmt->execute([
            ':code' => $code,
            ':label' => $label,
            ':id' => $id,
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare('DELETE FROM master_data WHERE id = :id AND category = :category');
        $stmt->execute([':id' => $id, ':category' => 'USER_TYPE']);
    }

    private function toEntity(array $row): Role
    {
        return new Role(
            id: (int) $row['id'],
            code: (string) $row['code'],
            label: (string) $row['label'],
        );
    }
}
