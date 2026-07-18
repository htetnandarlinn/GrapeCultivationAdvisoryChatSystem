<?php

namespace App\Infrastructure\Security;

final class ReCaptchaValidator
{
    private const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    public function validate(string $token): bool
    {
        $secret = RECAPTCHA_SECRET_KEY;

        if ($secret === '') {
            return true;
        }

        if ($token === '') {
            return false;
        }

        $response = file_get_contents(self::VERIFY_URL, false, stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query([
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
                ]),
            ],
        ]));

        if ($response === false) {
            return false;
        }

        $result = json_decode($response, true);

        return ($result['success'] ?? false) === true;
    }
}
