<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\Payment;

interface PaymentRepositoryInterface
{
    public function create(Payment $payment): void;

    public function findLatestByConsultationId(int $consultationId): ?Payment;

    /**
     * @param array<int> $consultationIds
     * @return array<int, Payment> map of consultation_id => latest Payment
     */
    public function findByConsultationIds(array $consultationIds): array;

    public function sumPaidAmount(): float;

    public function markSubmitted(int $consultationId, string $method, string $image, string $reference): void;

    public function markApproved(int $consultationId, string $adminName): void;

    public function markRejected(int $consultationId): void;

    public function markRefunded(int $consultationId, float $refundAmount, string $adminName): void;
}
