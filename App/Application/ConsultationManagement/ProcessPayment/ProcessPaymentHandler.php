<?php

namespace App\Application\ConsultationManagement\ProcessPayment;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Shared\Exceptions\PaymentException;
use PDO;

final class ProcessPaymentHandler
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private PDO $connection,
    ) {}

    public function handle(ProcessPaymentCommand $command): void
    {
        $this->connection->beginTransaction();

        try {
            $existing = $this->consultationRepository->findByIdempotencyKey($command->idempotencyKey);
            if ($existing !== null) {
                $this->connection->commit();
                return;
            }

            $consultation = $this->consultationRepository->findById($command->consultationId);
            if ($consultation === null) {
                throw new PaymentException('Consultation not found.');
            }

            if ($consultation->getFarmerId() !== $command->farmerId) {
                throw new PaymentException('Unauthorized payment attempt.');
            }

            $currentStatus = $consultation->getStatus()->getValue();
            if (!in_array($currentStatus, ['awaiting_payment', 'expired'], true)) {
                throw new PaymentException(
                    "Cannot process payment for consultation in '{$currentStatus}' status."
                );
            }

            if ($currentStatus === 'expired') {
                $consultation->renewPayment($command->idempotencyKey);
            } else {
                $consultation->markPaid($command->idempotencyKey);
            }

            $this->consultationRepository->update($consultation);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
