<?php

namespace App\Domain\UserManagement\Repositories;

use App\Domain\UserManagement\Entities\EmailVerification;

interface EmailVerificationRepositoryInterface
{
    public function save(EmailVerification $verification): void;

    public function update(EmailVerification $verification): void;

    public function findByToken(string $token): ?EmailVerification;

    public function findByUserId(int $userId): ?EmailVerification;

    public function deleteByUserId(int $userId): void;
}