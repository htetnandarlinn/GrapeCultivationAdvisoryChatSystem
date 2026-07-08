<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\RegisterUser\RegisterUserCommand;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Shared\Application\Validation\Validator;
use App\Shared\Exceptions\ValidationException;

final class RegisterRequestValidator
{
    public function validate(array $payload): RegisterUserCommand
    {
        $username = trim($payload['username'] ?? '');
        $email = trim($payload['email'] ?? '');
        $phone = trim($payload['phone'] ?? '');
        $address = trim($payload['address'] ?? '');
        $password = (string) ($payload['password'] ?? '');
        $confirmPassword = (string) ($payload['confirm_password'] ?? '');

        $validator = new Validator();

        $validator
            ->required('username', $username)
            ->minLength('username', $username, 3)
            ->required('email', $email)
            ->email('email', $email)
            
            ->required('phone', $phone)
            ->digits('phone', $phone)
            ->lengthBetween('phone', $phone, 11, 11)
            ->myanmarPhone('phone', $phone)

            ->required('address', $address)
            ->required('password', $password)
            ->minLength('password', $password, 8)
            ->required('confirm_password', $confirmPassword)
            ->match('confirm_password', $confirmPassword, $password);

        if ($validator->fails()) {
            throw new ValidationException($validator->getErrors());
        }

        return new RegisterUserCommand(
            id: null,
            username: $username,
            email: strtolower($email),
            phoneNumber: $phone,
            address: $address,
            passwordHash: password_hash($password, PASSWORD_DEFAULT),
            type: UserType::farmer()
        );
    }
}
