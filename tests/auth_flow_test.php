<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Application\UserManagement\LoginUser\LoginUserCommand;
use App\Application\UserManagement\LoginUser\LoginUserHandler;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\AuthRepositoryInterface;
use App\Domain\UserManagement\Services\UserAuthenticationService;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Shared\Exceptions\ValidationException;

final class FakeAuthRepository implements AuthRepositoryInterface
{
    public function __construct(private User $user)
    {
    }

    public function findByIdentifier(string $identifier): ?User
    {
        return $this->user;
    }

    public function verifyCredentials(string $identifier, string $password): bool
    {
        return true;
    }
}

$user = new User(
    id: 1,
    username: 'tester',
    email: new Email('tester@example.com'),
    phoneNumber: '123456789',
    address: 'Test address',
    passwordHash: password_hash('secret', PASSWORD_BCRYPT),
    type: UserType::farmer(),
    status: UserStatus::active(),
    isVerified: false,
    isLogin: false
);

$handler = new LoginUserHandler(
    new FakeAuthRepository($user),
    new UserAuthenticationService()
);

try {
    $handler->handle(new LoginUserCommand('tester@example.com', 'secret'));
    echo "Login guard test: DID NOT THROW (unexpected)\n";
    exit(1);
} catch (ValidationException $e) {
    $errors = $e->getErrors();
    if (($errors['email'] ?? '') !== 'Please verify your email before logging in.') {
        echo "Login guard test: expected verification error\n";
        exit(1);
    }
}

echo "Login guard test passed.\n";
