<?php

namespace App\Domain\ConsultationManagement\ValueObjects;

final class ConsultationStatus
{
    private const PENDING = 'pending';
    private const ASSIGNED = 'assigned';
    private const ACCEPTED = 'accepted';
    private const REJECTED = 'rejected';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function assigned(): self
    {
        return new self(self::ASSIGNED);
    }

    public static function accepted(): self
    {
        return new self(self::ACCEPTED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::PENDING => self::pending(),
            self::ASSIGNED => self::assigned(),
            self::ACCEPTED => self::accepted(),
            self::REJECTED => self::rejected(),
            default => throw new \InvalidArgumentException("Invalid consultation status: {$value}"),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
