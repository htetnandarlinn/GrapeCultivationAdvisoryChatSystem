<?php

namespace App\Application\UserManagement\ForgotPassword;

use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\Repositories\PasswordResetRepositoryInterface;
use App\Infrastructure\Mail\MailServiceInterface;
use App\Domain\UserManagement\Entities\PasswordReset;

final class ForgotPasswordHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordResetRepositoryInterface $passwordResetRepository,
        private MailServiceInterface $mailService,
    ) {}

    public function handle(ForgotPasswordCommand $command): ?string
    {
        $email = trim($command->email);

        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return null;
        }

        $token = bin2hex(random_bytes(24));
        $expiresAt = (new \DateTimeImmutable())->modify('+1 hour');

        $latest = $this->passwordResetRepository->findLatestByUserId($user->getId());
        $reuseToken = null;
        if ($latest !== null) {
            $age = (new \DateTimeImmutable())->getTimestamp() - $latest->getCreatedAt()->getTimestamp();
            if ($age < 300 && !$latest->isExpired()) {
                $reuseToken = $latest->getToken();
            } else {
                $this->passwordResetRepository->deleteByUserId($user->getId());
            }
        }

        if ($reuseToken === null) {
            $reset = new PasswordReset(
                null,
                $user->getId(),
                $token,
                $expiresAt
            );

            $this->passwordResetRepository->save($reset);
            $activeToken = $token;
        } else {
            $activeToken = $reuseToken;
        }

        $appUrl = null;

        if (defined('APP_URL') && APP_URL !== '') {
            $appUrl = APP_URL;
        }

        if (!empty($appUrl) && !filter_var($appUrl, FILTER_VALIDATE_URL)) {
            $candidate = trim($appUrl, '/');

            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

            if (preg_match('#^[a-z0-9.-]+(/.*)?$#i', $candidate)) {
                if (strpos($candidate, $host) === 0) {
                    $appUrl = $scheme . '://' . $candidate;
                } else {
                    $appUrl = $scheme . '://' . $host . '/' . $candidate;
                }
            } else {
                $appUrl = $scheme . '://' . $host . '/' . $candidate;
            }
        }

        if (empty($appUrl)) {
            $base = defined('BASE_URL') ? trim(BASE_URL, '/') : '';
            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $appUrl = $scheme . '://' . $host . '/' . ltrim($base, '/');
        }

        $appUrl = rtrim($appUrl, '/');
        $resetLink = $appUrl . '/reset-password?token=' . $token;

        $mailSent = false;
        try {
            $mailSent = (bool)$this->mailService->sendPasswordResetEmail(
                $user->getEmail()->getValue(),
                $user->getUsername(),
                $resetLink
            );
        } catch (\Throwable $e) {
            error_log('Password reset email failed: ' . $e->getMessage());
        }

        try {
            $logDir = dirname(__DIR__, 3) . '/storage';
            if (!is_dir($logDir)) {
                mkdir($logDir, 0777, true);
            }
            $logFile = $logDir . '/reset_links.log';
            $entry = sprintf("%s | email=%s | token=%s | link=%s | sent=%s\n",
                (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
                $user->getEmail()->getValue(),
                $activeToken,
                $resetLink,
                $mailSent ? 'true' : 'false'
            );
            file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {
            error_log('Failed to write reset log: ' . $e->getMessage());
        }

        return $activeToken;
    }
}
