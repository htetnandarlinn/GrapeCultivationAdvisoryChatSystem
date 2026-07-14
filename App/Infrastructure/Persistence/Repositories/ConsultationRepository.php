<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Entities\Consultation;
use App\Domain\ConsultationManagement\Repositories\ConsultationRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\ConsultationStatus;
use PDO;

final class ConsultationRepository implements ConsultationRepositoryInterface
{
    public function __construct(private PDO $connection) {}

    public function save(Consultation $consultation): void
    {
        $sql = '
            INSERT INTO consultations (farmer_id, title, description, status, expert_id, rejection_note, paid_at, expires_at, idempotency_key, payment_method, transaction_image, verified_by, verified_at, refund_status, refund_date, refund_amount, admin_notes, created_at, updated_at)
            VALUES (:farmer_id, :title, :description, :status, :expert_id, :rejection_note, :paid_at, :expires_at, :idempotency_key, :payment_method, :transaction_image, :verified_by, :verified_at, :refund_status, :refund_date, :refund_amount, :admin_notes, NOW(), NOW())
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':farmer_id' => $consultation->getFarmerId(),
            ':title' => $consultation->getTitle(),
            ':description' => $consultation->getDescription(),
            ':status' => $consultation->getStatus()->getValue(),
            ':expert_id' => $consultation->getExpertId(),
            ':rejection_note' => $consultation->getRejectionNote(),
            ':paid_at' => $this->formatDateTime($consultation->getPaidAt()),
            ':expires_at' => $this->formatDateTime($consultation->getExpiresAt()),
            ':idempotency_key' => $consultation->getIdempotencyKey(),
            ':payment_method' => $consultation->getPaymentMethod(),
            ':transaction_image' => $consultation->getTransactionImage(),
            ':verified_by' => $consultation->getVerifiedBy(),
            ':verified_at' => $this->formatDateTime($consultation->getVerifiedAt()),
            ':refund_status' => $consultation->getRefundStatus(),
            ':refund_date' => $this->formatDateTime($consultation->getRefundDate()),
            ':refund_amount' => $consultation->getRefundAmount(),
            ':admin_notes' => $consultation->getAdminNotes(),
        ]);

        $consultation->setId((int) $this->connection->lastInsertId());
    }

    public function findById(int $id): ?Consultation
    {
        $sql = 'SELECT * FROM consultations WHERE id = :id LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM consultations ORDER BY created_at DESC';
        $stmt = $this->connection->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function findByFarmer(int $farmerId): array
    {
        $sql = 'SELECT * FROM consultations WHERE farmer_id = :farmer_id ORDER BY created_at DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':farmer_id' => $farmerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function countAll(): int
    {
        $stmt = $this->connection->query('SELECT COUNT(*) FROM consultations');
        return (int) $stmt->fetchColumn();
    }

    public function findByExpert(int $expertId): array
    {
        $sql = 'SELECT * FROM consultations WHERE expert_id = :expert_id ORDER BY created_at DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':expert_id' => $expertId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function findByStatus(string $status): array
    {
        $sql = 'SELECT * FROM consultations WHERE status = :status ORDER BY created_at DESC';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':status' => $status]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function countByExpert(int $expertId): int
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM consultations WHERE expert_id = :expert_id');
        $stmt->execute([':expert_id' => $expertId]);
        return (int) $stmt->fetchColumn();
    }

    public function countDistinctFarmersByExpert(int $expertId): int
    {
        $stmt = $this->connection->prepare('SELECT COUNT(DISTINCT farmer_id) FROM consultations WHERE expert_id = :expert_id');
        $stmt->execute([':expert_id' => $expertId]);
        return (int) $stmt->fetchColumn();
    }

    public function findByIdempotencyKey(string $key): ?Consultation
    {
        $sql = 'SELECT * FROM consultations WHERE idempotency_key = :key LIMIT 1';
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':key' => $key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->mapToEntity($row) : null;
    }

    public function findExpiredActiveConsultations(): array
    {
        $sql = "SELECT * FROM consultations WHERE status = 'accepted' AND expires_at IS NOT NULL AND expires_at <= NOW()";
        $stmt = $this->connection->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn(array $row) => $this->mapToEntity($row), $rows);
    }

    public function update(Consultation $consultation): void
    {
        $sql = '
            UPDATE consultations
            SET status = :status,
                expert_id = :expert_id,
                rejection_note = :rejection_note,
                paid_at = :paid_at,
                expires_at = :expires_at,
                idempotency_key = :idempotency_key,
                payment_method = :payment_method,
                transaction_image = :transaction_image,
                verified_by = :verified_by,
                verified_at = :verified_at,
                refund_status = :refund_status,
                refund_date = :refund_date,
                refund_amount = :refund_amount,
                admin_notes = :admin_notes,
                updated_at = NOW()
            WHERE id = :id
        ';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            ':status' => $consultation->getStatus()->getValue(),
            ':expert_id' => $consultation->getExpertId(),
            ':rejection_note' => $consultation->getRejectionNote(),
            ':paid_at' => $this->formatDateTime($consultation->getPaidAt()),
            ':expires_at' => $this->formatDateTime($consultation->getExpiresAt()),
            ':idempotency_key' => $consultation->getIdempotencyKey(),
            ':payment_method' => $consultation->getPaymentMethod(),
            ':transaction_image' => $consultation->getTransactionImage(),
            ':verified_by' => $consultation->getVerifiedBy(),
            ':verified_at' => $this->formatDateTime($consultation->getVerifiedAt()),
            ':refund_status' => $consultation->getRefundStatus(),
            ':refund_date' => $this->formatDateTime($consultation->getRefundDate()),
            ':refund_amount' => $consultation->getRefundAmount(),
            ':admin_notes' => $consultation->getAdminNotes(),
            ':id' => $consultation->getId(),
        ]);
    }

    private function mapToEntity(array $row): Consultation
    {
        return new Consultation(
            id: (int) $row['id'],
            farmerId: (int) $row['farmer_id'],
            title: $row['title'],
            description: $row['description'] ?? '',
            status: ConsultationStatus::fromString($row['status']),
            expertId: $row['expert_id'] ? (int) $row['expert_id'] : null,
            rejectionNote: $row['rejection_note'] ?? null,
            paidAt: !empty($row['paid_at']) ? new \DateTimeImmutable($row['paid_at']) : null,
            expiresAt: !empty($row['expires_at']) ? new \DateTimeImmutable($row['expires_at']) : null,
            idempotencyKey: $row['idempotency_key'] ?? null,
            paymentMethod: $row['payment_method'] ?? null,
            transactionImage: $row['transaction_image'] ?? null,
            verifiedBy: $row['verified_by'] ?? null,
            verifiedAt: !empty($row['verified_at']) ? new \DateTimeImmutable($row['verified_at']) : null,
            refundStatus: $row['refund_status'] ?? null,
            refundDate: !empty($row['refund_date']) ? new \DateTimeImmutable($row['refund_date']) : null,
            refundAmount: isset($row['refund_amount']) ? (float) $row['refund_amount'] : null,
            adminNotes: $row['admin_notes'] ?? null,
            createdAt: new \DateTimeImmutable($row['created_at']),
            updatedAt: !empty($row['updated_at']) ? new \DateTimeImmutable($row['updated_at']) : null,
        );
    }

    private function formatDateTime(?\DateTimeImmutable $dateTime): ?string
    {
        return $dateTime?->format('Y-m-d H:i:s');
    }
}
