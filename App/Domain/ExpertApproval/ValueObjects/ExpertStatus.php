<?php

namespace App\Domain\ExpertApproval\ValueObjects;

final class ExpertStatus
{
    private const PENDING = 'pending';
    private const APPROVED = 'approved';
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

    public static function approved(): self
    {
        return new self(self::APPROVED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

