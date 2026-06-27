<?php

namespace App\Infrastructure\Persistence\Repositories;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\AuthRepositoryInterface;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;

final class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function findByIdentifier(string $identifier): ?User
    {
        return $this->userRepository->findByUsernameOrEmail($identifier);
    }

    public function verifyCredentials(string $identifier, string $password): bool
    {
        $user = $this->userRepository->findByUsernameOrEmail($identifier);
        if ($user === null) {
            return false;
        }

        return password_verify($password, $user->getPasswordHash());
    }
}
