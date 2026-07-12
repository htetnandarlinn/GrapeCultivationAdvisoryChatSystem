<?php

namespace App\Application\Messaging\GetConversationHistory;

final class GetConversationHistoryQuery
{
    public function __construct(
        public readonly int $consultationId,
    ) {}
}