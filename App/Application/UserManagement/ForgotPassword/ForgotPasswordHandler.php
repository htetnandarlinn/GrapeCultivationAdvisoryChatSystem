<?php

namespace App\Application\UserManagement\ForgotPassword;

use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Infrastructure\Persistence\Repositories\PasswordResetRepository;
use App\Infrastructure\Mail\PHPMailerService;
use App\Domain\UserManagement\Entities\PasswordReset;

final class ForgotPasswordHandler
{
    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->passwordResetRepository = new PasswordResetRepository();
        $this->mailService = new PHPMailerService();
    }

    public function handle(ForgotPasswordCommand $command): bool
    {
        $email = trim($command->email);

        $user = $this->userRepository->findByEmail($email);

        if ($user === null) {
            return false;
        }

        // Generate token
        $token = bin2hex(random_bytes(24));
        $expiresAt = (new \DateTimeImmutable())->modify('+1 hour');

        // Remove existing tokens for user
        // Check for recent token (cooldown) and reuse if still recent
        $latest = $this->passwordResetRepository->findLatestByUserId($user->getId());
        $reuseToken = null;
        if ($latest !== null) {
            $age = (new \DateTimeImmutable())->getTimestamp() - $latest->getCreatedAt()->getTimestamp();
            // If a token was issued less than 300 seconds ago, reuse it
            if ($age < 300 && !$latest->isExpired()) {
                $reuseToken = $latest->getToken();
            } else {
                // remove old tokens
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

        // Build a normalized base URL to use for reset links.
        $appUrl = null;

        if (defined('APP_URL') && APP_URL !== '') {
            $appUrl = APP_URL;
        }

        // If APP_URL exists but isn't a full URL, try to add scheme and host
        if (!empty($appUrl) && !filter_var($appUrl, FILTER_VALIDATE_URL)) {
            // Ensure no leading/trailing slashes
            $candidate = trim($appUrl, '/');

            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

            // If candidate already contains a host segment like 'localhost/foo', keep it
            if (preg_match('#^[a-z0-9.-]+(/.*)?$#i', $candidate)) {
                // Prepend scheme and host if candidate does not already start with host
                if (strpos($candidate, $host) === 0) {
                    $appUrl = $scheme . '://' . $candidate;
                } else {
                    $appUrl = $scheme . '://' . $host . '/' . $candidate;
                }
            } else {
                $appUrl = $scheme . '://' . $host . '/' . $candidate;
            }
        }

        // Ultimate fallback: build from server vars and BASE_URL
        if (empty($appUrl)) {
            $base = defined('BASE_URL') ? trim(BASE_URL, '/') : '';
            $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $appUrl = $scheme . '://' . $host . '/' . ltrim($base, '/');
        }

        // Normalize and build final reset link
        $appUrl = rtrim($appUrl, '/');
        $resetLink = $appUrl . '/reset-password?token=' . $token;

        // Send email and always log the reset link for local testing
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

        // Log reset link to storage for development/testing (append)
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

        return true;
    }
}
