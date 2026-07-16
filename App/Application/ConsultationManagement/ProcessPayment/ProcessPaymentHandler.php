<?php

namespace App\Application\ConsultationManagement\ProcessPayment;

use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\Shared\TransactionManagerInterface;
use App\Shared\Exceptions\PaymentException;

final class ProcessPaymentHandler
{
    public function __construct(
        private ConsultationRepositoryInterface $consultationRepository,
        private PaymentRepositoryInterface $paymentRepository,
        private TransactionManagerInterface $transactionManager,
    ) {}

    public function handle(ProcessPaymentCommand $command): void
    {
        $this->transactionManager->begin();

        try {
            $existing = $this->consultationRepository->findByIdempotencyKey($command->idempotencyKey);
            if ($existing !== null) {
                $this->transactionManager->commit();
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
            $this->paymentRepository->markSubmitted(
                $command->consultationId,
                $command->paymentMethod,
                $command->transactionImage,
                $command->idempotencyKey
            );

            $this->transactionManager->commit();
        } catch (\Throwable $e) {
            $this->transactionManager->rollBack();
            throw $e;
        }
    }
}
