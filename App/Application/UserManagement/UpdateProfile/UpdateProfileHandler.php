<?php

namespace App\Application\UserManagement\UpdateProfile;

use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Domain\UserManagement\ValueObjects\Email;

class UpdateProfileHandler
{
    public function __construct(
        private UserRepository $repository
    ) {}

    public function handle(UpdateProfileCommand $command): void
    {
        $user = $this->repository->findById($command->userId);

        if (!$user) {
            throw new \Exception("User not found");
        }

        // Update basic information
        $user->setUsername($command->username);
        $user->setEmail(new Email($command->email));
        $user->setPhoneNumber($command->phone ?? '');
        $user->setAddress($command->address ?? '');

        // Update password if provided
        if (!empty($command->password)) {
            $user->setPasswordHash(
                password_hash($command->password, PASSWORD_BCRYPT)
            );
        }

        // Upload profile image
        if (
            !empty($command->profileImage) &&
            isset($command->profileImage['error']) &&
            $command->profileImage['error'] === UPLOAD_ERR_OK
        ) {

            // Generate unique filename
            $filename = uniqid('profile_', true) . '.' .
                pathinfo($command->profileImage['name'], PATHINFO_EXTENSION);

            // Absolute upload directory
            $uploadDirectory = dirname(__DIR__, 4)
                . DIRECTORY_SEPARATOR
                . 'public'
                . DIRECTORY_SEPARATOR
                . 'uploads'
                . DIRECTORY_SEPARATOR
                . 'profile';

            // Create directory if it doesn't exist
            if (!is_dir($uploadDirectory)) {
                mkdir($uploadDirectory, 0777, true);
            }

            $destination = $uploadDirectory . DIRECTORY_SEPARATOR . $filename;

            // Move uploaded file
            if (!move_uploaded_file($command->profileImage['tmp_name'], $destination)) {
                throw new \Exception('Failed to upload profile image.');
            }

            // Save relative path into database
            $user->setProfileImage('/uploads/profile/' . $filename);
        }

        // Update timestamp
        $user->setUpdatedAt(new \DateTimeImmutable());

        // Save changes
        $this->repository->update($user);
    }
}