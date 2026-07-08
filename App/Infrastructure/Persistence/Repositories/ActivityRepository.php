<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Shared\Infrastructure\Database\Database;
use PDO;

class ActivityRepository
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }

    /**
     * Save a new system activity.
     */
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

    /**
     * Get latest activities.
     */
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

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $activities = [];

        foreach ($rows as $row) {

            $activities[] = [
                'message' => $row['activity'],
                'time' => date(
                    'h:i A',
                    strtotime($row['created_at'])
                ),
                'dot_class' => $this->getDotColor(
                    $row['user_role']
                )
            ];
        }

        return $activities;
    }

    private function getDotColor(?string $role): string
    {
        return match (strtoupper($role ?? '')) {

            'ADMIN' => 'bg-red-500',

            'EXPERT' => 'bg-blue-500',

            'FARMER' => 'bg-green-500',

            default => 'bg-gray-400',
        };
    }
}