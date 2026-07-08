<?php

namespace App\Presentation\Controllers\Farmer;

use App\Application\UserManagement\UpdateProfile\UpdateProfileCommand;
use App\Application\UserManagement\UpdateProfile\UpdateProfileHandler;
use App\Application\UserManagement\UpdateProfile\UpdateProfileRequestValidator;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Views\farmerView;

class ProfileController
{
    private UserRepository $repository;

    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    public function profile(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $user = $this->repository->findById($_SESSION['user_id']);

        if ($user === null) {
            session_destroy();
            redirect('/login');
            exit;
        }

        farmerView::render('farmer/profile', [
            'user' => $user
        ]);
    }

    public function edit(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $user = $this->repository->findById($_SESSION['user_id']);

        farmerView::render('farmer/update-profile', [
            'user' => $user
        ]);
    }

    public function update(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $validator = new UpdateProfileRequestValidator();

        $errors = $validator->validate($_POST, $_FILES);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/profile/edit');
            exit;
        }

        $command = new UpdateProfileCommand(
            userId: $_SESSION['user_id'],
            username: $_POST['username'],
            email: $_POST['email'],
            phone: $_POST['phone'] ?? '',
            address: $_POST['address'] ?? '',
            password: $_POST['password'] ?? null,
            profileImage: $_FILES['avatar'] ?? null
        );

        // THIS WAS MISSING
        $handler = new UpdateProfileHandler($this->repository);
        $handler->handle($command);

        // Reload latest user from database
        $updatedUser = $this->repository->findById($_SESSION['user_id']);

        if ($updatedUser) {
            $_SESSION['user_id'] = $updatedUser->getId();
            $_SESSION['user_role'] = $updatedUser->getType()->getValue();

            $_SESSION['user'] = [
                'id' => $updatedUser->getId(),
                'username' => $updatedUser->getUsername(),
                'email' => $updatedUser->getEmail()->getValue(),
                'role' => ucfirst($updatedUser->getType()->getValue()),
                'avatar' => $updatedUser->getProfileImage(),
            ];
        }
        $_SESSION['success'] = 'Profile updated successfully';

        redirect('/profile');
    }
}
