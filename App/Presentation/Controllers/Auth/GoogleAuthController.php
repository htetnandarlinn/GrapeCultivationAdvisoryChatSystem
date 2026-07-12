<?php

namespace App\Presentation\Controllers\Auth;

use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserStatus;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Domain\Activity\Repositories\ActivityRepositoryInterface;
use App\Infrastructure\Persistence\Repositories\PermissionRepository;
use App\Infrastructure\Persistence\Repositories\RoleRepository;

final class GoogleAuthController
{
    private const AUTH_URL = 'https://accounts.google.com/o/oauth2/v2/auth';
    private const TOKEN_URL = 'https://oauth2.googleapis.com/token';
    private const USERINFO_URL = 'https://www.googleapis.com/oauth2/v2/userinfo';

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private ActivityRepositoryInterface $activityRepository,
        private RoleRepository $roleRepo,
        private PermissionRepository $permRepo,
    ) {}

    public function redirect(): never
    {
        if (GOOGLE_CLIENT_ID === '' || GOOGLE_CLIENT_SECRET === '') {
            $_SESSION['errors'] = ['Google login is not configured yet. Please contact the administrator.'];
            redirect('/login');
            exit;
        }

        $params = http_build_query([
            'client_id' => GOOGLE_CLIENT_ID,
            'redirect_uri' => GOOGLE_REDIRECT_URI,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        header('Location: ' . self::AUTH_URL . '?' . $params);
        exit;
    }

    public function callback(): never
    {
        $code = $_GET['code'] ?? '';

        if ($code === '') {
            $_SESSION['errors'] = ['Google authentication failed: no authorization code received.'];
            redirect('/login');
            exit;
        }

        $tokenData = $this->exchangeCodeForToken($code);

        if ($tokenData === null || !isset($tokenData['access_token'])) {
            $_SESSION['errors'] = ['Google authentication failed: unable to obtain access token.'];
            redirect('/login');
            exit;
        }

        $googleUser = $this->fetchGoogleUser($tokenData['access_token']);

        if ($googleUser === null || !isset($googleUser['id'], $googleUser['email'])) {
            $_SESSION['errors'] = ['Google authentication failed: unable to fetch user info.'];
            redirect('/login');
            exit;
        }

        $googleId = $googleUser['id'];
        $email = $googleUser['email'];
        $name = $googleUser['name'] ?? explode('@', $email)[0];
        $avatar = $googleUser['picture'] ?? null;

        $existingUser = $this->userRepository->findByGoogleId($googleId);

        if ($existingUser !== null) {
            $this->loginUser($existingUser);
            exit;
        }

        $existingByEmail = $this->userRepository->findByEmail($email);

        if ($existingByEmail !== null) {
            $existingByEmail->setGoogleId($googleId);
            $this->userRepository->update($existingByEmail);
            $this->loginUser($existingByEmail);
            exit;
        }

        $user = new User(
            id: null,
            username: $this->generateUniqueUsername($name),
            email: new Email($email),
            phoneNumber: '',
            address: '',
            passwordHash: password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
            type: UserType::farmer(),
            status: UserStatus::active(),
            isVerified: true,
            isLogin: false,
            profileImage: $avatar,
            googleId: $googleId,
            createdAt: new \DateTimeImmutable(),
        );

        $this->userRepository->save($user);
        $this->loginUser($user);
        exit;
    }

    private function exchangeCodeForToken(string $code): ?array
    {
        $ch = curl_init(self::TOKEN_URL);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'code' => $code,
                'client_id' => GOOGLE_CLIENT_ID,
                'client_secret' => GOOGLE_CLIENT_SECRET,
                'redirect_uri' => GOOGLE_REDIRECT_URI,
                'grant_type' => 'authorization_code',
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return is_array($data) ? $data : null;
    }

    private function fetchGoogleUser(string $accessToken): ?array
    {
        $ch = curl_init(self::USERINFO_URL . '?access_token=' . urlencode($accessToken));
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
            CURLOPT_TIMEOUT => 10,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || $response === false) {
            return null;
        }

        $data = json_decode($response, true);
        return is_array($data) ? $data : null;
    }

    private function generateUniqueUsername(string $base): string
    {
        $username = preg_replace('/[^a-zA-Z0-9_]/', '_', strtolower(trim($base)));
        $username = substr($username, 0, 20);

        if ($this->userRepository->findByUsername($username) === null) {
            return $username;
        }

        for ($i = 1; $i <= 100; $i++) {
            $candidate = $username . $i;
            if ($this->userRepository->findByUsername($candidate) === null) {
                return $candidate;
            }
        }

        return 'user_' . bin2hex(random_bytes(4));
    }

    private function loginUser(User $user): never
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user->setLogin(true);
        $user->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Yangon')));
        $this->userRepository->update($user);

        $_SESSION['user_id'] = $user->getId();
        $_SESSION['user_role'] = $user->getType()->getValue();

        $_SESSION['user_permissions'] = $this->loadUserPermissions($user->getType()->getValue());

        $_SESSION['user'] = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => ucfirst($user->getType()->getValue()),
            'avatar' => $user->getProfileImage(),
        ];

        $this->activityRepository->logActivity(
            ucfirst($user->getType()->getValue())
                . ' "' . $user->getUsername()
                . '" logged in via Google.',
            $user->getId(),
            strtoupper($user->getType()->getValue())
        );

        $dest = match ($user->getType()->getValue()) {
            'farmer' => '/',
            'expert' => '/dashboard',
            'admin' => '/dashboard',
        };

        redirect($dest);
        exit;
    }

    private function loadUserPermissions(string $roleCode): array
    {
        $roles = $this->roleRepo->findAll();
        foreach ($roles as $role) {
            if (strcasecmp($role->getCode(), $roleCode) === 0) {
                $permissions = $this->permRepo->findPermissionsByUserTypeId($role->getId());
                return array_map(fn($p) => $p->getKey(), $permissions);
            }
        }
        return [];
    }
}
