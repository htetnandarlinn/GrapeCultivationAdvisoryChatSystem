<?php

namespace App\Domain\Messaging\ValueObjects;

final class MessageType
{
    private const TEXT = 'text';
    private const IMAGE = 'image';
    private const SYSTEM = 'system';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function text(): self
    {
        return new self(self::TEXT);
    }

    public static function image(): self
    {
        return new self(self::IMAGE);
    }

    public static function system(): self
    {
        return new self(self::SYSTEM);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

