<?php

namespace App\Domain\ConsultationManagement\Repositories;

use App\Domain\ConsultationManagement\Entities\Consultation;

interface ConsultationRepositoryInterface
{
    public function save(Consultation $consultation): void;

    public function findById(int $id): ?Consultation;

    public function findAll(): array;

    public function findByFarmer(int $farmerId): array;

    public function findByExpert(int $expertId): array;

    public function findByStatus(string $status): array;

    public function countAll(): int;

    public function countByExpert(int $expertId): int;

    public function countDistinctFarmersByExpert(int $expertId): int;

    public function findByIdempotencyKey(string $key): ?Consultation;

    public function findExpiredActiveConsultations(): array;

    public function findActiveByFarmerAndTitle(int $farmerId, string $title): array;

    /** @return array<int, array{month: string, total: int}> */
    public function getMonthlyConsultationTrend(int $months = 12): array;

    /** @return array<int, array{status: string, count: int}> */
    public function getConsultationStatusSummary(): array;

    /** @return array<int, array{expert_id: int, expert_name: string, answered_questions: int, pending_questions: int, avg_response_time_hours: float}> */
    public function getExpertPerformance(int $limit = 10): array;

    /** @return array<int, array{farmer_id: int, farmer_name: string, questions_submitted: int}> */
    public function getTopFarmers(int $limit = 10): array;

    /** @return array<int, array{expert_id: int, expert_name: string, questions_answered: int}> */
    public function getTopExperts(int $limit = 10): array;

    public function update(Consultation $consultation): void;
}
