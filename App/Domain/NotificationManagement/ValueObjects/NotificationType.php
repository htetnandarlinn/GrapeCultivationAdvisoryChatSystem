<?php

namespace App\Domain\NotificationManagement\ValueObjects;

final class NotificationType
{
    private const SYSTEM = 'system';
    private const EXPERT_REPLY = 'expert_reply';
    private const ANNOUNCEMENT = 'announcement';
    private const CONSULTATION_CREATED = 'consultation_created';
    private const CONSULTATION_ASSIGNED = 'consultation_assigned';
    private const CONSULTATION_ACCEPTED = 'consultation_accepted';
    private const CONSULTATION_REJECTED = 'consultation_rejected';
    private const MESSAGE_RECEIVED = 'message_received';
    private const ARTICLE_CREATED = 'article_created';
    private const ARTICLE_ACCEPTED = 'article_accepted';
    private const ARTICLE_REJECTED = 'article_rejected';

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

    public static function consultationCreated(): self
    {
        return new self(self::CONSULTATION_CREATED);
    }

    public static function consultationAssigned(): self
    {
        return new self(self::CONSULTATION_ASSIGNED);
    }

    public static function consultationAccepted(): self
    {
        return new self(self::CONSULTATION_ACCEPTED);
    }

    public static function consultationRejected(): self
    {
        return new self(self::CONSULTATION_REJECTED);
    }

    public static function messageReceived(): self
    {
        return new self(self::MESSAGE_RECEIVED);
    }

    public static function articleCreated(): self
    {
        return new self(self::ARTICLE_CREATED);
    }

    public static function articleAccepted(): self
    {
        return new self(self::ARTICLE_ACCEPTED);
    }

    public static function articleRejected(): self
    {
        return new self(self::ARTICLE_REJECTED);
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
