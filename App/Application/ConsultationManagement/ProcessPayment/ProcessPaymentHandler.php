<?php

namespace App\Application\ConsultationManagement\ProcessPayment;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Shared\Exceptions\PaymentException;
use PDO;

final class ProcessPaymentHandler
{
    private const CONSULTATION_FEE = 29.99;

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

            // Set status to payment_submitted (not directly accepted)
            $consultation->markPaymentSubmitted(
                $command->idempotencyKey,
                $command->paymentMethod,
                $command->transactionImage
            );
            $this->consultationRepository->update($consultation);

            // Update the existing payment record with submission details
            $stmt = $this->connection->prepare('
                UPDATE payments
                SET payment_status = :payment_status,
                    payment_method = :payment_method,
                    transaction_image = :transaction_image,
                    transaction_reference = :transaction_reference,
                    payment_date = NOW(),
                    updated_at = NOW()
                WHERE consultation_id = :consultation_id
            ');
            $stmt->execute([
                ':payment_status' => 'SUBMITTED',
                ':payment_method' => $command->paymentMethod,
                ':transaction_image' => $command->transactionImage,
                ':transaction_reference' => $command->idempotencyKey,
                ':consultation_id' => $consultation->getId(),
            ]);

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
}
