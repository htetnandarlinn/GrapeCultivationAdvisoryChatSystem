<?php

namespace App\Domain\UserManagement\ValueObjects;

use InvalidArgumentException;

final class UserStatus
{
    private const ACTIVE = 'active';
    private const INACTIVE = 'inactive';
    private const PENDING = 'pending';
    private const BLOCKED = 'blocked';

    private string $value;

    private function __construct(string $value)
    {
        $value = strtolower(trim($value));

        $allowed = [
            self::ACTIVE,
            self::INACTIVE,
            self::PENDING,
            self::BLOCKED,
        ];

        if (!in_array($value, $allowed, true)) {
            throw new InvalidArgumentException(
                "Invalid user status: {$value}"
            );
        }

        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function inactive(): self
    {
        return new self(self::INACTIVE);
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function isActive(): bool
    {
        return $this->value === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this->value === self::INACTIVE;
    }

    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }

    public function isBlocked(): bool
    {
        return $this->value === self::BLOCKED;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}