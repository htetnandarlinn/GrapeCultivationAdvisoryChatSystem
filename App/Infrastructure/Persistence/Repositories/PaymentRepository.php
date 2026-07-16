<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Entities\Payment;
use App\Domain\ConsultationManagement\Repositories\PaymentRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\PaymentStatus;
use PDO;

final class PaymentRepository implements PaymentRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function create(Payment $payment): void
    {
        $stmt = $this->connection->prepare('
            INSERT INTO payments
                (consultation_id, farmer_id, amount, payment_date, start_date, expiry_date,
                 payment_status, transaction_reference, payment_method, created_at, updated_at)
            VALUES
                (:consultation_id, :farmer_id, :amount, NOW(), CURDATE(), DATE_ADD(CURDATE(), INTERVAL 30 DAY),
                 :payment_status, :transaction_reference, :payment_method, NOW(), NOW())
        ');
        $stmt->execute([
            ':consultation_id' => $payment->getConsultationId(),
            ':farmer_id' => $payment->getFarmerId(),
            ':amount' => $payment->getAmount(),
            ':payment_status' => $payment->getStatus()->getValue(),
            ':transaction_reference' => $payment->getTransactionReference(),
            ':payment_method' => $payment->getPaymentMethod(),
        ]);
    }

    public function findLatestByConsultationId(int $consultationId): ?Payment
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM payments WHERE consultation_id = :cid ORDER BY id DESC LIMIT 1'
        );
        $stmt->execute([':cid' => $consultationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->toEntity($row) : null;
    }

    public function findByConsultationIds(array $consultationIds): array
    {
        if (empty($consultationIds)) {
            return [];
        }

        $ids = array_values($consultationIds);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->connection->prepare(
            "SELECT * FROM payments WHERE consultation_id IN ({$placeholders}) ORDER BY id DESC"
        );
        $stmt->execute($ids);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $latest = [];
        foreach ($rows as $row) {
            $entity = $this->toEntity($row);
            $latest[$entity->getConsultationId()] = $entity;
        }

        return $latest;
    }

    public function sumPaidAmount(): float
    {
        $stmt = $this->connection->prepare(
            "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE payment_status = 'PAID'"
        );
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function markSubmitted(int $consultationId, string $method, string $image, string $reference): void
    {
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
            ':payment_status' => PaymentStatus::submitted()->getValue(),
            ':payment_method' => $method,
            ':transaction_image' => $image,
            ':transaction_reference' => $reference,
            ':consultation_id' => $consultationId,
        ]);
    }

    public function markApproved(int $consultationId, string $adminName): void
    {
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                payment_date = NOW(),
                start_date = CURDATE(),
                expiry_date = DATE_ADD(CURDATE(), INTERVAL 30 DAY),
                verified_by = :verified_by,
                verified_at = NOW(),
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => PaymentStatus::paid()->getValue(),
            ':verified_by' => $adminName,
            ':consultation_id' => $consultationId,
        ]);
    }

    public function markRejected(int $consultationId): void
    {
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                transaction_image = NULL,
                payment_method = NULL,
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => PaymentStatus::rejected()->getValue(),
            ':consultation_id' => $consultationId,
        ]);
    }

    public function markRefunded(int $consultationId, float $refundAmount, string $adminName): void
    {
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET payment_status = :payment_status,
                refund_status = :refund_status,
                refund_date = NOW(),
                refund_amount = :refund_amount,
                verified_by = :verified_by,
                verified_at = NOW(),
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':payment_status' => PaymentStatus::refunded()->getValue(),
            ':refund_status' => 'refunded',
            ':refund_amount' => $refundAmount,
            ':verified_by' => $adminName,
            ':consultation_id' => $consultationId,
        ]);
    }

    public function markPayoutReleased(int $consultationId, float $payoutAmount, string $adminName): void
    {
        $note = 'Expert payout released: $' . number_format($payoutAmount, 2) . ' by ' . $adminName;
        $stmt = $this->connection->prepare('
            UPDATE payments
            SET admin_notes = :admin_notes,
                updated_at = NOW()
            WHERE consultation_id = :consultation_id
        ');
        $stmt->execute([
            ':admin_notes' => $note,
            ':consultation_id' => $consultationId,
        ]);
    }

    private function toEntity(array $row): Payment
    {
        return new Payment(
            id: (int) $row['id'],
            consultationId: (int) $row['consultation_id'],
            farmerId: (int) $row['farmer_id'],
            amount: (float) $row['amount'],
            status: PaymentStatus::fromString((string) $row['payment_status']),
            paymentDate: $row['payment_date'] ? new \DateTimeImmutable($row['payment_date']) : null,
            startDate: $row['start_date'] ? new \DateTimeImmutable($row['start_date']) : null,
            expiryDate: $row['expiry_date'] ? new \DateTimeImmutable($row['expiry_date']) : null,
            transactionReference: $row['transaction_reference'],
            paymentMethod: $row['payment_method'],
            transactionImage: $row['transaction_image'],
            verifiedBy: $row['verified_by'],
            verifiedAt: $row['verified_at'] ? new \DateTimeImmutable($row['verified_at']) : null,
            refundStatus: $row['refund_status'],
            refundDate: $row['refund_date'] ? new \DateTimeImmutable($row['refund_date']) : null,
            refundAmount: $row['refund_amount'] !== null ? (float) $row['refund_amount'] : null,
            adminNotes: $row['admin_notes'] ?? null,
            createdAt: $row['created_at'] ? new \DateTimeImmutable($row['created_at']) : null,
            updatedAt: $row['updated_at'] ? new \DateTimeImmutable($row['updated_at']) : null,
        );
    }
}
