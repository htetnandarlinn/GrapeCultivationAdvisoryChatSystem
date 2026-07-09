<?php

namespace App\Domain\KnowledgeBase\ValueObjects;

final class ArticleStatus
{
    private const PENDING = 'pending';
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

    public static function accepted(): self
    {
        return new self(self::ACCEPTED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::PENDING => self::pending(),
            self::ACCEPTED => self::accepted(),
            self::REJECTED => self::rejected(),
            'draft' => self::pending(),
            'published' => self::accepted(),
            default => throw new \InvalidArgumentException("Unknown article status: {$value}"),
        };
    }
}

