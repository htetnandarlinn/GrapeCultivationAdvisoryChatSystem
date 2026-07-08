<?php

namespace App\Domain\RoleManagement\Entities;

final class Role
{
    public function __construct(
        private readonly int $id,
        private readonly string $code,
        private readonly string $label,
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getLabel(): string
    {
        return $this->label;
    }
}
