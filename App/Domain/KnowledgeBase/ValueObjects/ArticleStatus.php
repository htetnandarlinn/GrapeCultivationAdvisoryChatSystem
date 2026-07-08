<?php

namespace App\Domain\KnowledgeBase\ValueObjects;

final class ArticleStatus
{
    private const DRAFT = 'draft';
    private const PUBLISHED = 'published';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function draft(): self
    {
        return new self(self::DRAFT);
    }

    public static function published(): self
    {
        return new self(self::PUBLISHED);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public static function fromValue(string $value): self
    {
        return match ($value) {
            self::DRAFT => self::draft(),
            self::PUBLISHED => self::published(),
            default => throw new \InvalidArgumentException("Unknown article status: {$value}"),
        };
    }
}

