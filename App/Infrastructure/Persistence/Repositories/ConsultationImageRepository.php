<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Repositories\ConsultationImageRepositoryInterface;
use PDO;

final class ConsultationImageRepository implements ConsultationImageRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
    }

    public function findByConsultationIds(array $consultationIds): array
    {
        if (empty($consultationIds)) {
            return [];
        }

        $ids = array_values($consultationIds);
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->connection->prepare(
            "SELECT * FROM consultation_images WHERE consultation_id IN ($placeholders) ORDER BY image_id ASC"
        );
        $stmt->execute($ids);

        $map = [];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $map[(int) $row['consultation_id']][] = $row;
        }

        return $map;
    }

    public function findByConsultationId(int $consultationId): array
    {
        $stmt = $this->connection->prepare(
            'SELECT * FROM consultation_images WHERE consultation_id = :cid ORDER BY image_id ASC'
        );
        $stmt->execute([':cid' => $consultationId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findFirstImagePath(int $consultationId): ?string
    {
        $stmt = $this->connection->prepare(
            'SELECT image_path FROM consultation_images WHERE consultation_id = :cid ORDER BY image_id ASC LIMIT 1'
        );
        $stmt->execute([':cid' => $consultationId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['image_path'] : null;
    }

    public function create(int $consultationId, string $imagePath): void
    {
        $stmt = $this->connection->prepare(
            'INSERT INTO consultation_images (consultation_id, image_path, created_at) VALUES (:cid, :path, NOW())'
        );
        $stmt->execute([
            ':cid' => $consultationId,
            ':path' => $imagePath,
        ]);
    }
}
