<?php

namespace App\Domain\ConsultationManagement\ValueObjects;

final class PayoutStatus
{
    private const PENDING = 'pending';
    private const RELEASED = 'released';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function released(): self
    {
        return new self(self::RELEASED);
    }

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            self::RELEASED => self::released(),
            default => self::pending(),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(PayoutStatus $other): bool
    {
        return $this->value === $other->value;
    }

    public function isReleased(): bool
    {
        return $this->value === self::RELEASED;
    }
}
