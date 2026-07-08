<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use PDO;

class ActivityRepository implements ActivityRepositoryInterface
{
    public function __construct(private PDO $connection) {}

    public function logActivity(
        string $activity,
        ?int $userId = null,
        ?string $userRole = null
    ): void {

        $sql = "
            INSERT INTO system_activities (
                activity,
                user_id,
                user_role
            )
            VALUES (
                :activity,
                :user_id,
                :user_role
            )
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            ':activity' => $activity,
            ':user_id' => $userId,
            ':user_role' => $userRole
        ]);
    }

    public function getRecentActivities(int $limit = 8): array
    {
        $sql = "
            SELECT *
            FROM system_activities
            ORDER BY created_at DESC
            LIMIT :limit
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
