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
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

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
            ) VALUES (
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
            $question->setQuestionId((int)$this->connection->lastInsertId());
        }

        return $success;
    }

    public function findById(int $questionId): ?Question
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM questions WHERE question_id = :id LIMIT 1"
        );
        $stmt->execute([':id' => $questionId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Question(
            (int)$row['question_id'],
            (int)$row['farmer_id'],
            (int)$row['category_id'],
            $row['title'],
            $row['description'],
            $row['image'],
            (int)$row['status_id'],
            $row['expert_id'] ? (int)$row['expert_id'] : null,
            $row['answer'],
            $row['created_at'],
            $row['updated_at']
        );
    }

    public function findByFarmer(int $farmerId): array
    {
        $stmt = $this->connection->prepare(
            "SELECT
                q.*,
                cat.label AS category_name,
                stat.label AS status_name,
                e.username AS expert_name,
                e.profile_image AS expert_avatar
            FROM questions q
            LEFT JOIN master_data cat
                ON cat.id = q.category_id
                AND cat.category = 'QUESTION_CATEGORY'
            LEFT JOIN master_data stat
                ON stat.id = q.status_id
                AND stat.category = 'QUESTION_STATUS'
            LEFT JOIN users e
                ON e.user_id = q.expert_id
            WHERE q.farmer_id = :farmer_id
            ORDER BY q.created_at DESC"
        );
        $stmt->execute([':farmer_id' => $farmerId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findCategories(): array
    {
        $stmt = $this->connection->prepare(
            "SELECT id, label FROM master_data WHERE category = 'QUESTION_CATEGORY' ORDER BY id ASC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findPending(): array
    {
        $stmt = $this->connection->prepare(
            "SELECT * FROM questions WHERE status_id = 7 ORDER BY created_at DESC"
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function answerQuestion(
        int $questionId,
        int $expertId,
        string $answer,
        int $statusId
    ): bool {
        $stmt = $this->connection->prepare(
            "UPDATE questions
             SET expert_id = :expert_id,
                 answer = :answer,
                 status_id = :status_id,
                 updated_at = NOW()
             WHERE question_id = :question_id"
        );

        return $stmt->execute([
            ':expert_id'   => $expertId,
            ':answer'      => $answer,
            ':status_id'   => $statusId,
            ':question_id' => $questionId,
        ]);
    }
}