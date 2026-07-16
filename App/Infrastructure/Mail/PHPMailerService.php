<?php

namespace App\Infrastructure\Mail;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

final class PHPMailerService implements MailServiceInterface
{
    public function sendVerificationEmail(
        string $email,
        string $username,
        string $verificationLink
    ): bool {

        $mail = new PHPMailer(true);

        try {

            /*
            |--------------------------------------------------------------------------
            | SMTP Configuration
            |--------------------------------------------------------------------------
            */

            $mail->isSMTP();
            $mail->Host = MailConfig::HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MailConfig::USERNAME;
            $mail->Password = MailConfig::PASSWORD;
            $mail->SMTPSecure = MailConfig::ENCRYPTION;
            $mail->Port = MailConfig::PORT;

            // Enable for debugging only if needed
            // $mail->SMTPDebug = 2;
            $mail->Debugoutput = 'html';

            /*
            |--------------------------------------------------------------------------
            | Sender
            |--------------------------------------------------------------------------
            */

            $mail->setFrom(
                MailConfig::FROM_EMAIL,
                MailConfig::FROM_NAME
            );

            /*
            |--------------------------------------------------------------------------
            | Receiver
            |--------------------------------------------------------------------------
            */

            $mail->addAddress(
                $email,
                $username
            );

            /*
            |--------------------------------------------------------------------------
            | Email Content
            |--------------------------------------------------------------------------
            */

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Verify Your Email Address';

            $template = __DIR__ . '/Templates/verify-email.php';

            if (!file_exists($template)) {
                throw new \RuntimeException(
                    'Email template not found: ' . $template
                );
            }

            $data = [
                'username' => $username,
                'verificationLink' => $verificationLink,
            ];

            extract($data, EXTR_SKIP);

            ob_start();
            require $template;
            $mail->Body = ob_get_clean();

            $mail->AltBody =
                "Hello {$username}\n\n" .
                "Please verify your email:\n" .
                "{$verificationLink}";

            $result = $mail->send();

            // Log only (does not display in browser)
            error_log('Verification email sent: ' . ($result ? 'true' : 'false'));

            return $result;

        } catch (Exception $e) {

            throw new \RuntimeException(
                'Unable to send verification email. ' .
                $mail->ErrorInfo
            );
        }
    }

    public function sendPasswordResetEmail(
        string $email,
        string $username,
        string $resetLink
    ): bool {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = MailConfig::HOST;
            $mail->SMTPAuth = true;
            $mail->Username = MailConfig::USERNAME;
            $mail->Password = MailConfig::PASSWORD;
            $mail->SMTPSecure = MailConfig::ENCRYPTION;
            $mail->Port = MailConfig::PORT;

            $mail->Debugoutput = 'html';

            $mail->setFrom(
                MailConfig::FROM_EMAIL,
                MailConfig::FROM_NAME
            );

            $mail->addAddress(
                $email,
                $username
            );

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Reset Your Password';

            $template = __DIR__ . '/Templates/reset-password.php';

            if (!file_exists($template)) {
                throw new \RuntimeException('Email template not found: ' . $template);
            }

            $data = [
                'username' => $username,
                'resetLink' => $resetLink,
            ];

            extract($data, EXTR_SKIP);

            ob_start();
            require $template;
            $mail->Body = ob_get_clean();

            $mail->AltBody = "Hello {$username}\n\nPlease reset your password:\n{$resetLink}";

            $result = $mail->send();

            error_log('Password reset email sent: ' . ($result ? 'true' : 'false'));

            return $result;

        } catch (Exception $e) {
            throw new \RuntimeException('Unable to send password reset email. ' . $mail->ErrorInfo);
        }
    }
}
