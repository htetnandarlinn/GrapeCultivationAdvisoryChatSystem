<?php

namespace App\Domain\KnowledgeBase\Services;

use App\Domain\KnowledgeBase\Entities\Article;

final class ArticleDomainService
{
    public function accept(Article $article): void
    {
        $article->setStatus(\App\Domain\KnowledgeBase\ValueObjects\ArticleStatus::accepted());
    }

    public function reject(Article $article): void
    {
        $article->setStatus(\App\Domain\KnowledgeBase\ValueObjects\ArticleStatus::rejected());
    }
}

