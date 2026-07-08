<?php

namespace App\Domain\KnowledgeBase\Repositories;

use App\Domain\KnowledgeBase\Entities\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $article): int;

    public function findById(int $id): ?Article;

    public function findAll(?int $authorId = null): array;

    public function findPublished(): array;

    public function delete(int $id): void;
}
