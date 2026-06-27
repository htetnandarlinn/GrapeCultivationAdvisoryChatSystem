<?php

namespace App\Domain\ExpertApproval\Services;

use App\Domain\ExpertApproval\Entities\ExpertApproval;

final class ExpertApprovalDomainService
{
    public function approve(ExpertApproval $approval): void
    {
        $approval->approve();
    }

    public function reject(ExpertApproval $approval): void
    {
        $approval->reject();
    }
}

