<?php

namespace App\Application\UserManagement\LoginUser;

use App\Domain\UserManagement\Repositories\AuthRepositoryInterface;
use App\Domain\UserManagement\Services\UserAuthenticationService;
use App\Domain\UserManagement\Entities\User;
use App\Shared\Exceptions\ValidationException;
use App\Shared\Helpers\ValidationHelper;

final class LoginUserHandler
{
    public function __construct(
        private AuthRepositoryInterface $authRepository,
        private UserAuthenticationService $authenticationService,
    ) {
    }

    public function handle(LoginUserCommand $command): User
    {
        ValidationHelper::assertNotEmpty($command->email, 'Username or Email is required.');
        ValidationHelper::assertNotEmpty($command->password, 'Password is required.');

        $user = $this->authRepository->findByIdentifier($command->email);
        if ($user === null) {
            throw new ValidationException(['login' => 'Invalid username/email or password.']);
        }

        if (!$this->authRepository->verifyCredentials($command->email, $command->password)) {
            throw new ValidationException(['login' => 'Invalid username/email or password.']);
        }

        $this->authenticationService->ensureCanAuthenticate($user);

        return $user;
    }
}
