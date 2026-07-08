<?php

namespace App\Infrastructure\Mail;

interface MailServiceInterface
{
    public function sendVerificationEmail(
        string $email,
        string $username,
        string $verificationLink
    ): bool;
    
    public function sendPasswordResetEmail(
        string $email,
        string $username,
        string $resetLink
    ): bool;
}