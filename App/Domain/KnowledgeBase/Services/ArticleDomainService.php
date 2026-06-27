<?php

namespace App\Domain\KnowledgeBase\Services;

use App\Domain\KnowledgeBase\Entities\Article;

final class ArticleDomainService
{
    public function publish(Article $article): void
    {
        $article->publish();
    }
}

