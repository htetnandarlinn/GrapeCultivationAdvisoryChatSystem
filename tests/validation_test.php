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
    $errors = $e->getErrors();
    if (!is_array($errors) || !isset($errors['username'], $errors['email'], $errors['password'], $errors['confirm_password'])) {
        echo "Register validator: missing expected field errors\n";
        exit(1);
    }
}

$validEmailPayload = [
    'username' => 'validuser',
    'email' => ' htetnandarlinn14@gmail.com ',
    'phone' => '09123456789',
    'address' => 'Yangon',
    'password' => 'Pass1234',
    'confirm_password' => 'Pass1234',
    'role' => 'farmer',
];

try {
    $command = $registerValidator->validate($validEmailPayload);
    if ($command->email !== 'htetnandarlinn14@gmail.com') {
        echo "Register validator: email was not normalized correctly\n";
        exit(1);
    }
} catch (ValidationException $e) {
    echo "Register validator: whitespace-padded email should be accepted\n";
    exit(1);
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
    $errors = $e->getErrors();
    if (($errors['username_or_email'] ?? '') !== 'Username or email is required.') {
        echo "Login validator: expected username/email required message\n";
        exit(1);
    }
}

echo "Validation tests passed.\n";
