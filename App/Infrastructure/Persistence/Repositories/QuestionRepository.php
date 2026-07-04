<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\ConsultationManagement\Entities\Question;
use App\Domain\ConsultationManagement\Repositories\QuestionRepositoryInterface;
use App\Shared\Infrastructure\Database\Database;
use PDO;

class QuestionRepository implements QuestionRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = (new Database())->getConnection();

        $this->connection->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );

        $this->connection->setAttribute(
            PDO::ATTR_EMULATE_PREPARES,
            true
        );
    }

    /* ===================== SAVE ===================== */

    public function save(Question $question): bool
    {
        $sql = "
            INSERT INTO questions (
                farmer_id,
                category_id,
                title,
                description,
                image,
                status_id,
                expert_id,
                answer,
                created_at,
                updated_at
            )
            VALUES (
                :farmer_id,
                :category_id,
                :title,
                :description,
                :image,
                :status_id,
                :expert_id,
                :answer,
                NOW(),
                NOW()
            )
        ";

        $stmt = $this->connection->prepare($sql);

        $success = $stmt->execute([
            ':farmer_id'   => $question->getFarmerId(),
            ':category_id' => $question->getCategoryId(),
            ':title'       => $question->getTitle(),
            ':description' => $question->getDescription(),
            ':image'       => $question->getImage(),
            ':status_id'   => $question->getStatusId(),
            ':expert_id'   => $question->getExpertId(),
            ':answer'      => $question->getAnswer(),
        ]);

        if ($success) {
            $question->setQuestionId(
                (int)$this->connection->lastInsertId()
            );
        }

        return $success;
    }

    /* ===================== FIND BY ID ===================== */

    public function findById(int $questionId): ?Question
    {
        return null;
    }

    /* ===================== FIND BY FARMER ===================== */

    public function findByFarmer(int $farmerId): array
    {
        return [];
    }

    /* ===================== FIND PENDING ===================== */

    public function findPending(): array
    {
        return [];
    }

    /* ===================== ANSWER QUESTION ===================== */

    public function answerQuestion(
        int $questionId,
        int $expertId,
        string $answer,
        int $statusId
    ): bool {
        return false;
    }
}