<?php

namespace App\Domain\UserManagement\ValueObjects;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address.');
        }

        $this->value = strtolower(trim($value));
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

