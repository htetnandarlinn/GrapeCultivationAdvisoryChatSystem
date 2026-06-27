<?php

namespace App\Presentation\Controllers\Auth;

use App\Application\UserManagement\LoginUser\LoginUserCommand;
use App\Shared\Exceptions\ValidationException;
use App\Shared\Application\Validation\Validator;

final class LoginRequestValidator
{
    public function validate(array $payload): LoginUserCommand
    {
        $identifier = trim((string) (($payload['username_or_email'] ?? $payload['email'] ?? '') ));
        $password = (string) ($payload['password'] ?? '');

        $validator = new Validator();

        $validator
            ->required('username_or_email', $identifier)
            ->required('password', $password);

        if ($validator->fails()) {
            throw new ValidationException(
                $validator->getErrors()
            );
        }

        return new LoginUserCommand(
            $identifier,
            $password,
        );
    }
}
