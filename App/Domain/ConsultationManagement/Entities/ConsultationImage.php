<?php

namespace App\Domain\ConsultationManagement\Entities;

final class ConsultationImage
{
    private string $consultationId;
    private string $path;

    public function __construct(string $consultationId, string $path)
    {
        $this->consultationId = $consultationId;
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}

