<?php

namespace App\Domain\ConsultationManagement\ValueObjects;

final class ConsultationStatus
{
    private const PENDING = 'pending';
    private const ASSIGNED = 'assigned';
    private const EXPERT_ACCEPTED = 'expert_accepted';
    private const AWAITING_PAYMENT = 'awaiting_payment';
    private const PAYMENT_SUBMITTED = 'payment_submitted';
    private const ACCEPTED = 'accepted';
    private const CHAT_STARTED = 'chat_started';
    private const COMPLETED = 'completed';
    private const CLOSED = 'closed';
    private const REJECTED = 'rejected';
    private const EXPIRED = 'expired';

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

    public static function expertAccepted(): self
    {
        return new self(self::EXPERT_ACCEPTED);
    }

    public static function awaitingPayment(): self
    {
        return new self(self::AWAITING_PAYMENT);
    }

    public static function paymentSubmitted(): self
    {
        return new self(self::PAYMENT_SUBMITTED);
    }

    public static function accepted(): self
    {
        return new self(self::ACCEPTED);
    }

    public static function chatStarted(): self
    {
        return new self(self::CHAT_STARTED);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function closed(): self
    {
        return new self(self::CLOSED);
    }

    public static function rejected(): self
    {
        return new self(self::REJECTED);
    }

    public static function expired(): self
    {
        return new self(self::EXPIRED);
    }

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            self::PENDING => self::pending(),
            self::ASSIGNED => self::assigned(),
            self::EXPERT_ACCEPTED => self::expertAccepted(),
            self::AWAITING_PAYMENT => self::awaitingPayment(),
            self::PAYMENT_SUBMITTED => self::paymentSubmitted(),
            'active', self::ACCEPTED => self::accepted(),
            self::CHAT_STARTED => self::chatStarted(),
            self::COMPLETED => self::completed(),
            self::CLOSED => self::closed(),
            self::REJECTED => self::rejected(),
            self::EXPIRED => self::expired(),
            default => throw new \InvalidArgumentException("Invalid consultation status: {$value}"),
        };
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
