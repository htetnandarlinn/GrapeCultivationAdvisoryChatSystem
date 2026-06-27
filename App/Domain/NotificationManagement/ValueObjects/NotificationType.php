<?php

namespace App\Domain\NotificationManagement\ValueObjects;

final class NotificationType
{
    private const SYSTEM = 'system';
    private const EXPERT_REPLY = 'expert_reply';
    private const ANNOUNCEMENT = 'announcement';

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function system(): self
    {
        return new self(self::SYSTEM);
    }

    public static function expertReply(): self
    {
        return new self(self::EXPERT_REPLY);
    }

    public static function announcement(): self
    {
        return new self(self::ANNOUNCEMENT);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

