<?php

namespace App\Domain\Activity\Repositories;

interface ActivityRepositoryInterface
{
    public function logActivity(string $activity, ?int $userId = null, ?string $userRole = null): void;

    public function getRecentActivities(int $limit = 8): array;
}
