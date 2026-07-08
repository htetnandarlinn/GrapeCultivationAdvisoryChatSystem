<?php

namespace App\Domain\UserManagement\ValueObjects;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $normalizedValue = trim($value);

        if (!filter_var($normalizedValue, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address.');
        }

        $this->value = strtolower($normalizedValue);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

