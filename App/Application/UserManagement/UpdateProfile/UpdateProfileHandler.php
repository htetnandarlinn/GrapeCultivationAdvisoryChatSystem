<?php

namespace App\Application\UserManagement\UpdateProfile;

use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Domain\UserManagement\ValueObjects\Email;

class UpdateProfileHandler
{
    public function __construct(
        private UserRepositoryInterface $repository
    ) {}

    public function handle(UpdateProfileCommand $command): void
    {
        $user = $this->repository->findById($command->userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        $user->setUsername($command->username);
        $user->setEmail(new Email($command->email));
        $user->setPhoneNumber($command->phone ?? '');
        $user->setAddress($command->address ?? '');

        if (!empty($command->password)) {
            $user->setPasswordHash(
                password_hash($command->password, PASSWORD_BCRYPT)
            );
        }

        if (
            !empty($command->profileImage) &&
            isset($command->profileImage['error']) &&
            $command->profileImage['error'] === UPLOAD_ERR_OK
        ) {
            $filename = uniqid('profile_', true) . '.' .
                pathinfo($command->profileImage['name'], PATHINFO_EXTENSION);

            $uploadDirectory = dirname(__DIR__, 4)
                . DIRECTORY_SEPARATOR
                . 'public'
                . DIRECTORY_SEPARATOR
                . 'uploads'
                . DIRECTORY_SEPARATOR
                . 'profile';

            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;

            if (!move_uploaded_file($command->profileImage['tmp_name'], $destination)) {
                throw new \Exception('Failed to upload profile image.');
            }

            $user->setProfileImage('/uploads/profile/' . $filename);
        }

        $user->setUpdatedAt(new \DateTimeImmutable());

        $this->repository->update($user);
    }
}
