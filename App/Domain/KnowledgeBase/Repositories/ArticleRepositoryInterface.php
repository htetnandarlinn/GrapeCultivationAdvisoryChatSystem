<?php

namespace App\Domain\KnowledgeBase\Repositories;

use App\Domain\KnowledgeBase\Entities\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $article): void;

    public function findById(string $id): ?Article;

    public function findPublished(): array;
}

