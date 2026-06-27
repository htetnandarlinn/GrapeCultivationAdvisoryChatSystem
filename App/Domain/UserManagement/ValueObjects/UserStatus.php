<?php

namespace App\Domain\UserManagement\ValueObjects;

final class UserStatus
{
    private const ACTIVE = 'active';
    private const BLOCKED = 'blocked';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function blocked(): self
    {
        return new self(self::BLOCKED);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

