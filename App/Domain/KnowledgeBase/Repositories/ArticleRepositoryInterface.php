<?php

namespace App\Domain\KnowledgeBase\Repositories;

use App\Domain\KnowledgeBase\Entities\Article;

interface ArticleRepositoryInterface
{
    public function save(Article $article): int;

    public function findById(int $id): ?Article;

    public function findAll(?int $authorId = null): array;

    public function findPublished(): array;

    public function countAll(): int;

    public function countByAuthor(int $authorId): int;

    public function countImagesByAuthor(int $authorId): int;

    public function delete(int $id): void;
}
