<?php

namespace App\Application\ConsultationManagement\Payment;

use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;

final class PricingService
{
    private const CONSULTATION_FEE = 29.99;
    private const REFUND_RATE = 0.8;
    private const EXPERT_PAYOUT_RATE = 0.8;
    private const PLATFORM_FEE_RATE = 0.2;

    public function getConsultationFee(): float
    {
        return self::CONSULTATION_FEE;
    }

    public function getExpertPayoutRate(): float
    {
        return self::EXPERT_PAYOUT_RATE;
    }

    public function getPlatformFeeRate(): float
    {
        return self::PLATFORM_FEE_RATE;
    }

    public function calculateGrossAmount(): float
    {
        return self::CONSULTATION_FEE;
    }

    public function calculatePlatformFee(float $amount): float
    {
        return round($amount * self::PLATFORM_FEE_RATE, 2);
    }

    public function calculateExpertPayout(float $amount): float
    {
        return round($amount * self::EXPERT_PAYOUT_RATE, 2);
    }

    public function calculateRefundAmount(string $status): float
    {
        return match ($status) {
            'rejected' => self::CONSULTATION_FEE,
            'assigned', 'expert_accepted' => round(self::CONSULTATION_FEE * self::REFUND_RATE, 2),
            'accepted', 'chat_started' => self::CONSULTATION_FEE,
            'payment_submitted', 'awaiting_payment' => 0.0,
            default => 0.0,
        };
    }

    public function getRefundNote(string $status): string
    {
        return match ($status) {
            'rejected' => 'Full refund - expert rejected assignment',
            'assigned', 'expert_accepted' => '80% refund - farmer cancelled before expert started',
            'accepted', 'chat_started' => 'Full refund - admin cancelled',
            'payment_submitted', 'awaiting_payment' => 'Payment receipt rejected, no charge made',
            default => '',
        };
    }
}
