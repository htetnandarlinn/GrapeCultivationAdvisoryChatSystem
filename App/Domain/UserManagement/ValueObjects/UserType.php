<?php

namespace App\Domain\UserManagement\ValueObjects;

final class UserType
{
    private const FARMER = 'farmer';
    private const EXPERT = 'expert';
    private const ADMIN = 'admin';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function farmer(): self
    {
        return new self(self::FARMER);
    }

    public static function expert(): self
    {
        return new self(self::EXPERT);
    }

    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

