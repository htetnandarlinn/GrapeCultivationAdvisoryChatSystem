<?php

namespace App\Domain\ConsultationManagement\Events;

final class ConsultationAnswered
{
    public function __construct(public readonly string $consultationId, public readonly string $expertId)
    {
    }
}

