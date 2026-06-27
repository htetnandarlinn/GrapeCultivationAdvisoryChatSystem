<?php

namespace App\Domain\KnowledgeBase\Events;

final class ArticlePublished
{
    public function __construct(public readonly string $articleId)
    {
    }
}

