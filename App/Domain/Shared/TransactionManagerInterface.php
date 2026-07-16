<?php

namespace App\Domain\Shared;

interface TransactionManagerInterface
{
    public function begin(): void;

    public function commit(): void;

    public function rollBack(): void;
}
