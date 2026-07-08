<?php

namespace App\Domain\PermissionManagement\Entities;

final class Permission
{
    public function __construct(
        private readonly int $id,
        private readonly string $key,
        private readonly string $name,
        private readonly string $description = '',
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
