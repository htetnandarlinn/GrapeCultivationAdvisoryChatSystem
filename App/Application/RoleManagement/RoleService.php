<?php

namespace App\Application\RoleManagement;

use App\Domain\RoleManagement\Repositories\RoleRepositoryInterface;

final class RoleService
{
    public function __construct(
        private RoleRepositoryInterface $repository,
    ) {}

    public function generateCode(string $name): string
    {
        return strtolower(preg_replace('/\s+/', '_', trim($name)));
    }

    public function create(string $name): int
    {
        $code = $this->generateCode($name);

        if ($this->repository->existsByCode($code)) {
            throw new \RuntimeException('A role with this name already exists.');
        }

        return $this->repository->create($code, $name);
    }

    public function update(int $id, string $name): void
    {
        $code = $this->generateCode($name);

        if ($this->repository->existsByCode($code, $id)) {
            throw new \RuntimeException('A role with this name already exists.');
        }

        $this->repository->update($id, $code, $name);
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
