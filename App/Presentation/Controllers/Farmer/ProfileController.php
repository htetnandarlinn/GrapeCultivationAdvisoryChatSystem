<?php

namespace App\Presentation\Controllers\Farmer;

use App\Application\UserManagement\UpdateProfile\UpdateProfileCommand;
use App\Application\UserManagement\UpdateProfile\UpdateProfileHandler;
use App\Application\UserManagement\UpdateProfile\UpdateProfileRequestValidator;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Controllers\AuthorizesPermissions;
use App\Presentation\Views\farmerView;

class ProfileController
{
    use AuthorizesPermissions;

    public function __construct(
        private UserRepositoryInterface $repository,
        private UpdateProfileHandler $updateHandler,
    ) {}

    #[Permission('farmer.profile.view', 'View Profile')]
    public function profile(): void
    {
        $this->authorize('farmer.profile.view');

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

    #[Permission('farmer.profile.edit', 'Edit Profile')]
    public function edit(): void
    {
        $this->authorize('farmer.profile.edit');

        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $user = $this->repository->findById($_SESSION['user_id']);

        farmerView::render('farmer/update-profile', [
            'user' => $user
        ]);
    }

    #[Permission('farmer.profile.edit', 'Edit Profile')]
    public function update(): void
    {
        $this->authorize('farmer.profile.edit');

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

        $this->updateHandler->handle($command);

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
