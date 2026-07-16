<?php

namespace App\Domain\ConsultationManagement\ValueObjects;

final class PaymentStatus
{
    private const PENDING = 'PENDING';
    private const SUBMITTED = 'SUBMITTED';
    private const PAID = 'PAID';
    private const REJECTED = 'REJECTED';
    private const REFUNDED = 'REFUNDED';

    private function __construct(private string $value) {}

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function submitted(): self
    {
        return new self(self::SUBMITTED);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public static function refunded(): self
    {
        return new self(self::REFUNDED);
    }

    public static function fromString(string $value): self
    {
        return match (strtoupper($value)) {
            self::PENDING => self::pending(),
            self::SUBMITTED => self::submitted(),
            self::PAID => self::paid(),
            self::REJECTED => self::rejected(),
            self::REFUNDED => self::refunded(),
            default => throw new \InvalidArgumentException("Invalid payment status: {$value}"),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }
}
