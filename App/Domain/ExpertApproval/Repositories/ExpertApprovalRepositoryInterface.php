<?php

namespace App\Domain\ExpertApproval\Repositories;

use App\Domain\ExpertApproval\Entities\ExpertApproval;

interface ExpertApprovalRepositoryInterface
{
    public function save(ExpertApproval $approval): void;

    public function findPending(): array;
}

