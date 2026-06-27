<?php

namespace App\Domain\KnowledgeBase\Entities;

use App\Domain\KnowledgeBase\ValueObjects\ArticleStatus;

final class Article
{
    private string $id;
    private string $title;
    private string $content;
    private ArticleStatus $status;
    private string $authorId;
    private \DateTimeImmutable $publishedAt;

    public function __construct(string $id, string $title, string $content, string $authorId)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->status = ArticleStatus::draft();
        $this->publishedAt = new \DateTimeImmutable();
    }

    public function publish(): void
    {
        $this->status = ArticleStatus::published();
    }
}

