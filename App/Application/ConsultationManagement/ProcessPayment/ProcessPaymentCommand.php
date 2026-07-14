<?php

namespace App\Application\ConsultationManagement\ProcessPayment;

final class ProcessPaymentCommand
{
    public function __construct(
        public readonly int $consultationId,
        public readonly int $farmerId,
        public readonly string $idempotencyKey,
        public readonly ?string $paymentMethod = null,
        public readonly ?string $transactionImage = null,
    ) {}
}
