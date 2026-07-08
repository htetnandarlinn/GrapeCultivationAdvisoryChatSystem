<?php

namespace App\Application\UserManagement\UpdateProfile;

final class UpdateProfileCommand
{
    public function __construct(
        public int $userId,
        public string $username,
        public string $email,
        public ?string $phone,
        public ?string $address,
        public ?string $password,
        public ?array $profileImage
    ) {}
}
