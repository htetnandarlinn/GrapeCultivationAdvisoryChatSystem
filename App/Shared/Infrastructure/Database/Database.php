<?php

namespace App\Shared\Infrastructure\Database;

use PDO;
use PDOException;

final class Database
{
    private string $host = 'localhost';
    private string $database = 'grapecultivation01';
    private string $username = 'root';
    private string $password = '';

    private ?PDO $connection = null;

    public function getConnection(): PDO
    {
        if ($this->connection === null) {

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=utf8mb4',
                $this->host,
                $this->database
            );

            try {

                $this->connection = new PDO(
                    $dsn,
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]
                );

            } catch (PDOException $exception) {

                throw new PDOException(
                    'Database connection failed.',
                    (int)$exception->getCode(),
                    $exception
                );

            }

        }

        return $this->connection;
    }
}