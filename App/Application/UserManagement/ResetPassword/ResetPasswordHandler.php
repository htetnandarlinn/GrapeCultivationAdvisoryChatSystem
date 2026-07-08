<?php

namespace App\Application\UserManagement\ResetPassword;

use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\Repositories\PasswordResetRepositoryInterface;

final class ResetPasswordHandler
{
    public function __construct(
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(ResetPasswordCommand $command): bool
    {
        $token = trim($command->token);
        $password = trim($command->password);

        $reset = $this->passwordResetRepository->findByToken($token);

        if ($reset === null || $reset->isExpired()) {
            return false;
        }

        $user = $this->userRepository->findById($reset->getUserId());

        if ($user === null) {
            return false;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $user->setPasswordHash($hash);

        $this->userRepository->update($user);

        $this->passwordResetRepository->deleteByUserId($user->getId());

        return true;
    }
}
