<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\KnowledgeBase\Entities\Article;
use App\Domain\KnowledgeBase\Repositories\ArticleRepositoryInterface;
use App\Domain\KnowledgeBase\ValueObjects\ArticleStatus;
use PDO;

final class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(private PDO $connection)
    {
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function save(Article $article): int
    {
        if ($article->getId() !== null) {
            return $this->update($article);
        }

        $stmt = $this->connection->prepare(
            'INSERT INTO articles (title, content, image, author_id, status, rejection_note, created_at)
             VALUES (:title, :content, :image, :author_id, :status, :rejection_note, :created_at)'
        );

        $stmt->execute([
            ':title' => $article->getTitle(),
            ':content' => $article->getContent(),
            ':image' => $article->getImage(),
            ':author_id' => $article->getAuthorId(),
            ':status' => $article->getStatus()->getValue(),
            ':rejection_note' => $article->getRejectionNote(),
            ':created_at' => $article->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);

        return (int) $this->connection->lastInsertId();
    }

    private function update(Article $article): int
    {
        $stmt = $this->connection->prepare(
            'UPDATE articles SET title = :title, content = :content, image = :image,
             status = :status, rejection_note = :rejection_note, updated_at = :updated_at WHERE id = :id'
        );

        $stmt->execute([
            ':title' => $article->getTitle(),
            ':content' => $article->getContent(),
            ':image' => $article->getImage(),
            ':status' => $article->getStatus()->getValue(),
            ':rejection_note' => $article->getRejectionNote(),
            ':updated_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ':id' => $article->getId(),
        ]);

        return $article->getId();
    }

    public function findById(int $id): ?Article
    {
        $stmt = $this->connection->prepare(
            'SELECT id, title, content, image, author_id, status, rejection_note, created_at, updated_at
             FROM articles WHERE id = :id LIMIT 1'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $this->toEntity($row) : null;
    }

    public function findAll(?int $authorId = null): array
    {
        $sql = 'SELECT id, title, content, image, author_id, status, rejection_note, created_at, updated_at FROM articles';
        $params = [];

        if ($authorId !== null) {
            $sql .= ' WHERE author_id = :author_id';
            $params[':author_id'] = $authorId;
        }

        $sql .= ' ORDER BY created_at DESC';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return array_map(fn(array $row): Article => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function countAll(): int
    {
        $stmt = $this->connection->query('SELECT COUNT(*) FROM articles');
        return (int) $stmt->fetchColumn();
    }

    public function countByAuthor(int $authorId): int
    {
        $stmt = $this->connection->prepare('SELECT COUNT(*) FROM articles WHERE author_id = :author_id');
        $stmt->execute([':author_id' => $authorId]);
        return (int) $stmt->fetchColumn();
    }

    public function countImagesByAuthor(int $authorId): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) FROM articles WHERE author_id = :author_id AND image IS NOT NULL AND image != ''");
        $stmt->execute([':author_id' => $authorId]);
        return (int) $stmt->fetchColumn();
    }

    public function findPublished(): array
    {
        $stmt = $this->connection->prepare(
            'SELECT id, title, content, image, author_id, status, rejection_note, created_at, updated_at
             FROM articles WHERE status = :status ORDER BY created_at DESC'
        );
        $stmt->execute([':status' => 'accepted']);

        return array_map(fn(array $row): Article => $this->toEntity($row), $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function delete(int $id): void
    {
        $stmt = $this->connection->prepare('DELETE FROM articles WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    private function toEntity(array $row): Article
    {
        return new Article(
            id: (int) $row['id'],
            title: $row['title'],
            content: $row['content'],
            authorId: $row['author_id'],
            image: $row['image'],
            status: ArticleStatus::fromValue($row['status']),
            rejectionNote: $row['rejection_note'] ?? null,
            createdAt: new \DateTimeImmutable($row['created_at']),
            updatedAt: $row['updated_at'] ? new \DateTimeImmutable($row['updated_at']) : null,
        );
    }
}
