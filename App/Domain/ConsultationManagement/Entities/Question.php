<?php

namespace App\Domain\ConsultationManagement\Entities;

class Question
{
    private ?int $questionId;
    private int $farmerId;
    private int $categoryId;
    private string $title;
    private string $description;
    private ?string $image;
    private int $statusId;
    private ?int $expertId;
    private ?string $answer;
    private ?string $createdAt;
    private ?string $updatedAt;


    public function __construct(
    ?int $questionId,
    int $farmerId,
    int $categoryId,
    string $title,
    string $description,
    ?string $image,
    int $statusId,
    ?int $expertId = null,
    ?string $answer = null,
    ?string $createdAt = null,
    ?string $updatedAt = null
) {
    $this->questionId = $questionId;
    $this->farmerId = $farmerId;
    $this->categoryId = $categoryId;
    $this->title = $title;
    $this->description = $description;
    $this->image = $image;
    $this->statusId = $statusId;
    $this->expertId = $expertId;
    $this->answer = $answer;
    $this->createdAt = $createdAt;
    $this->updatedAt = $updatedAt;
}
public function getQuestionId(): ?int
{
    return $this->questionId;
}

public function getFarmerId(): int
{
    return $this->farmerId;
}

public function getCategoryId(): int
{
    return $this->categoryId;
}

public function getTitle(): string
{
    return $this->title;
}

public function getDescription(): string
{
    return $this->description;
}

public function getImage(): ?string
{
    return $this->image;
}

public function getStatusId(): int
{
    return $this->statusId;
}

public function getExpertId(): ?int
{
    return $this->expertId;
}

public function getAnswer(): ?string
{
    return $this->answer;
}

public function getCreatedAt(): ?string
{
    return $this->createdAt;
}

public function getUpdatedAt(): ?string
{
    return $this->updatedAt;
}

public function setQuestionId(int $questionId): void
{
    $this->questionId = $questionId;
}
}