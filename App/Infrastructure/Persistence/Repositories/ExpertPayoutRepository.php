<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Entities\ExpertPayout;
use App\Domain\ConsultationManagement\Repositories\ExpertPayoutRepositoryInterface;
use App\Domain\ConsultationManagement\ValueObjects\PayoutStatus;
use PDO;

final class ExpertPayoutRepository implements ExpertPayoutRepositoryInterface
{
    public function __construct(private PDO $connection) {}

    public function save(ExpertPayout $payout): void
    {
        if ($payout->getId() === null) {
            $stmt = $this->connection->prepare(
                'INSERT INTO expert_payouts (consultation_id, payment_id, expert_id, farmer_id, gross_amount, platform_fee, net_amount, status, released_at, released_by, created_at, updated_at)
                 VALUES (:consultation_id, :payment_id, :expert_id, :farmer_id, :gross_amount, :platform_fee, :net_amount, :status, :released_at, :released_by, NOW(), NOW())'
            );
            $stmt->execute([
                ':consultation_id' => $payout->getConsultationId(),
                ':payment_id' => $payout->getPaymentId(),
                ':expert_id' => $payout->getExpertId(),
                ':farmer_id' => $payout->getFarmerId(),
                ':gross_amount' => $payout->getGrossAmount(),
                ':platform_fee' => $payout->getPlatformFee(),
                ':net_amount' => $payout->getNetAmount(),
                ':status' => $payout->getStatus()->getValue(),
                ':released_at' => $this->formatDateTime($payout->getReleasedAt()),
                ':released_by' => $payout->getReleasedBy(),
            ]);
            $payout->setId((int) $this->connection->lastInsertId());
            return;
        }

        $stmt = $this->connection->prepare(
            'UPDATE expert_payouts
             SET status = :status, released_at = :released_at, released_by = :released_by, updated_at = NOW()
             WHERE id = :id'
        );
        $stmt->execute([
            ':status' => $payout->getStatus()->getValue(),
            ':released_at' => $this->formatDateTime($payout->getReleasedAt()),
            ':released_by' => $payout->getReleasedBy(),
            ':id' => $payout->getId(),
        ]);
    }

    public function findById(int $id): ?ExpertPayout
    {
        $stmt = $this->connection->prepare('SELECT * FROM expert_payouts WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToEntity($row) : null;
    }

    public function findByConsultationId(int $consultationId): ?ExpertPayout
    {
        $stmt = $this->connection->prepare('SELECT * FROM expert_payouts WHERE consultation_id = :cid LIMIT 1');
        $stmt->execute([':cid' => $consultationId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $this->mapToEntity($row) : null;
    }

    public function findByExpertId(int $expertId): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM expert_payouts WHERE expert_id = :eid ORDER BY created_at DESC');
        $stmt->execute([':eid' => $expertId]);
        return array_map([$this, 'mapToEntity'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query('SELECT * FROM expert_payouts ORDER BY created_at DESC');
        return array_map([$this, 'mapToEntity'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function sumNetByExpertId(int $expertId): float
    {
        $stmt = $this->connection->prepare('SELECT COALESCE(SUM(net_amount), 0) FROM expert_payouts WHERE expert_id = :eid AND status = :status');
        $stmt->execute([':eid' => $expertId, ':status' => PayoutStatus::released()->getValue()]);
        return (float) $stmt->fetchColumn();
    }

    public function sumPendingNet(): float
    {
        $stmt = $this->connection->prepare('SELECT COALESCE(SUM(net_amount), 0) FROM expert_payouts WHERE status = :status');
        $stmt->execute([':status' => PayoutStatus::pending()->getValue()]);
        return (float) $stmt->fetchColumn();
    }

    public function sumReleasedNet(): float
    {
        $stmt = $this->connection->prepare('SELECT COALESCE(SUM(net_amount), 0) FROM expert_payouts WHERE status = :status');
        $stmt->execute([':status' => PayoutStatus::released()->getValue()]);
        return (float) $stmt->fetchColumn();
    }

    private function mapToEntity(array $row): ExpertPayout
    {
        return new ExpertPayout(
            id: (int) $row['id'],
            consultationId: (int) $row['consultation_id'],
            paymentId: $row['payment_id'] !== null ? (int) $row['payment_id'] : null,
            expertId: (int) $row['expert_id'],
            farmerId: (int) $row['farmer_id'],
            grossAmount: (float) $row['gross_amount'],
            platformFee: (float) $row['platform_fee'],
            netAmount: (float) $row['net_amount'],
            status: PayoutStatus::fromString($row['status']),
            releasedAt: !empty($row['released_at']) ? new \DateTimeImmutable($row['released_at']) : null,
            releasedBy: $row['released_by'] ?? null,
            createdAt: new \DateTimeImmutable($row['created_at']),
            updatedAt: !empty($row['updated_at']) ? new \DateTimeImmutable($row['updated_at']) : null,
        );
    }

    private function formatDateTime(?\DateTimeImmutable $dateTime): ?string
    {
        return $dateTime?->format('Y-m-d H:i:s');
    }
}
