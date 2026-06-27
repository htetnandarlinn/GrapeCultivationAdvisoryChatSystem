<?php

namespace App\Domain\ConsultationManagement\ValueObjects;

final class ConsultationStatus
{
    private const PENDING = 'pending';
    private const ANSWERED = 'answered';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function answered(): self
    {
        return new self(self::ANSWERED);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

