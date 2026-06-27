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
        $password = (string)($payload['password'] ?? '');
        $confirmPassword = (string)($payload['confirm_password'] ?? '');
        $role = strtolower(trim($payload['role'] ?? ''));

        $validator = new Validator();

        $validator
            ->required('username', $username)
            ->minLength('username', $username, 3)

            ->required('email', $email)
            ->email('email', $email)

            ->required('phone', $phone)
            ->digits('phone', $phone)
            ->lengthBetween('phone', $phone, 11, 11)

            ->required('address', $address)

            ->required('password', $password)
            ->minLength('password', $password, 8)

            ->required('confirm_password', $confirmPassword)
            ->match('confirm_password', $confirmPassword, $password)

            ->required('role', $role);

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
            type: $this->mapRoleToUserType($role)
        );
    }

    private function mapRoleToUserType(string $role): UserType
    {
        return match ($role) {
            'farmer' => UserType::farmer(),
            'expert' => UserType::expert(),
            'admin' => UserType::admin(),
            default => throw new ValidationException([
                'role' => 'Please select a valid role.'
            ]),
        };
    }
}