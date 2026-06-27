<?php

namespace App\Infrastructure\FileStorage;

final class ConsultationImageStorage
{
    private string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = rtrim($basePath, '\\');
    }

    public function store(string $consultationId, string $sourcePath): string
    {
        // TODO: implement image storage and return stored path.
        return sprintf('%s\\%s.jpg', $this->basePath, $consultationId);
    }
}

