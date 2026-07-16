<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Shared\TransactionManagerInterface;
use PDO;

final class TransactionManager implements TransactionManagerInterface
{
    public function __construct(private PDO $connection) {}

    public function begin(): void
    {
        $this->connection->beginTransaction();
    }

    public function commit(): void
    {
        $this->connection->commit();
    }

    public function rollBack(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }
}
