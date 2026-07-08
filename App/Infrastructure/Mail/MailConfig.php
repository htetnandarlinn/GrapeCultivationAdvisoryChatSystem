<?php

namespace App\Infrastructure\Mail;
use PHPMailer\PHPMailer\PHPMailer;

final class MailConfig
{
    public const HOST = 'smtp.gmail.com';

    public const PORT = 587;

    public const USERNAME = 'ngalngal86426@gmail.com';

    public const PASSWORD = 'hhmrgbefubyfpbtr';

    public const FROM_EMAIL = 'ngalngal86426@gmail.com';

    public const FROM_NAME = 'Grape Cultivation Advisory System';

    public const ENCRYPTION = PHPMailer::ENCRYPTION_STARTTLS;
}

