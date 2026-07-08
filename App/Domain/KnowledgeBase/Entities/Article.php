<?php

namespace App\Domain\KnowledgeBase\Entities;

use App\Domain\KnowledgeBase\ValueObjects\ArticleStatus;

final class Article
{
    private ?int $id;
    private string $title;
    private string $content;
    private ?string $image;
    private ArticleStatus $status;
    private string $authorId;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public function __construct(
        ?int $id,
        string $title,
        string $content,
        string $authorId,
        ?string $image = null,
        ?ArticleStatus $status = null,
        ?\DateTimeImmutable $createdAt = null,
        ?\DateTimeImmutable $updatedAt = null,
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->image = $image;
        $this->status = $status ?? ArticleStatus::draft();
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getStatus(): ArticleStatus
    {
        return $this->status;
    }

    public function getAuthorId(): string
    {
        return $this->authorId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function publish(): void
    {
        $this->status = ArticleStatus::published();
    }

    public function setStatus(ArticleStatus $status): void
    {
        $this->status = $status;
    }
}
