<?php

namespace App\Shared\Exceptions;

class PaymentException extends \RuntimeException
{
    public function __construct(string $message = 'Payment processing failed.')
    {
        parent::__construct($message);
    }
}
