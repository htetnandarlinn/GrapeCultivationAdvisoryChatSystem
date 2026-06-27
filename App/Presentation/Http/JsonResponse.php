<?php

namespace App\Presentation\Http;

final class JsonResponse
{
    public static function send(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
    }

    public static function error(string $message, int $statusCode = 500): void
    {
        self::send(['error' => $message], $statusCode);
    }
}
