<?php

namespace App\Domain\ExpertApproval\Events;

final class ExpertApproved
{
    public function __construct(public readonly string $expertId)
    {
    }
}

