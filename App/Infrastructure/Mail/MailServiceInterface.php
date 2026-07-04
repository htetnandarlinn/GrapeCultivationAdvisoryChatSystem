<?php

namespace App\Infrastructure\Mail;

interface MailServiceInterface
{
    public function sendVerificationEmail(
        string $email,
        string $username,
        string $verificationLink
    ): bool;
}