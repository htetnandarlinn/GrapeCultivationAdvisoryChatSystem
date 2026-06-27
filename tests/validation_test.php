<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Presentation\Controllers\Auth\RegisterRequestValidator;
use App\Presentation\Controllers\Auth\LoginRequestValidator;
use App\Shared\Exceptions\ValidationException;

$registerValidator = new RegisterRequestValidator();
$loginValidator = new LoginRequestValidator();

$invalidRegisterPayload = [
    'username' => 'jo',
    'email' => 'invalid-email',
    'password' => '123',
    'confirm_password' => '1234',
];

try {
    $registerValidator->validate($invalidRegisterPayload);
    echo "Register validator: DID NOT THROW (unexpected)\n";
    exit(1);
} catch (ValidationException $e) {
    $errors = json_decode($e->getMessage(), true);
    if (!is_array($errors) || !isset($errors['username'], $errors['email'], $errors['password'], $errors['confirm_password'])) {
        echo "Register validator: missing expected field errors\n";
        exit(1);
    }
}

$invalidLoginPayload = [
    'username' => '',
    'email' => '',
    'password' => '',
];

try {
    $loginValidator->validate($invalidLoginPayload);
    echo "Login validator: DID NOT THROW (unexpected)\n";
    exit(1);
} catch (ValidationException $e) {
    if (stripos($e->getMessage(), 'Username or Email is required.') === false) {
        echo "Login validator: expected username/email required message\n";
        exit(1);
    }
}

echo "Validation tests passed.\n";
