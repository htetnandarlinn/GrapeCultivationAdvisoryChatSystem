<?php

namespace App\Application\UserManagement\ResetPassword;

use App\Infrastructure\Persistence\Repositories\PasswordResetRepository;
use App\Infrastructure\Persistence\Repositories\UserRepository;

final class ResetPasswordHandler
{
    public function __construct()
    {
        $this->passwordResetRepository = new PasswordResetRepository();
        $this->userRepository = new UserRepository();
    }

    /**
     * Returns true on success, false on failure (invalid token)
     */
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

        // Remove used token
        $this->passwordResetRepository->deleteByUserId($user->getId());

        return true;
    }
}
