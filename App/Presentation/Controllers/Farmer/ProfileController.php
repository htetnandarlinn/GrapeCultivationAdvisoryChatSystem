<?php

namespace App\Presentation\Controllers\Farmer;

use App\Application\UserManagement\UpdateProfile\UpdateProfileCommand;
use App\Application\UserManagement\UpdateProfile\UpdateProfileHandler;
use App\Application\UserManagement\UpdateProfile\UpdateProfileRequestValidator;
use App\Domain\UserManagement\Repositories\UserRepositoryInterface;
use App\Presentation\Attributes\Permission;
use App\Presentation\Views\View;

class ProfileController
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private UpdateProfileHandler $updateHandler,
    ) {}

    #[Permission('profile.view', 'View Profile')]
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

        View::render('farmer/profile', [
            'user' => $user
        ]);
    }

    #[Permission('profile.view', 'View Profile')]
    public function frontendProfile(): void
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

        View::render('farmer/frontend-profile', [
            'user' => $user
        ], 'app');
    }

    #[Permission('profile.view', 'View Profile')]
    public function frontendEdit(): void
    {
        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $user = $this->repository->findById($_SESSION['user_id']);

        View::render('farmer/frontend-update-profile', [
            'user' => $user
        ], 'app');
    }

    #[Permission('profile.edit', 'Edit Profile')]
    public function edit(): void
    {


        if (!isset($_SESSION['user_id'])) {
            redirect('/login');
            exit;
        }

        $user = $this->repository->findById($_SESSION['user_id']);

        View::render('farmer/update-profile', [
            'user' => $user
        ]);
    }

    #[Permission('profile.edit', 'Edit Profile')]
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
            $editRoute = ($_SESSION['user_role'] ?? '') === 'farmer' ? '/my-profile/edit' : '/profile/edit';
            redirect($editRoute);
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

        $userRole = $_SESSION['user_role'] ?? '';
        $username = $_SESSION['user']['username'] ?? 'A user';
        if ($userRole === 'farmer') {
            notifyAllByRole('admin', "Farmer {$username} has updated their profile.", 'profile_update', null);
            notifyAllByRole('farmer', "Farmer {$username} has updated their profile.", 'profile_update', null);
        } elseif ($userRole === 'expert') {
            notifyAllByRole('admin', "Expert {$username} has updated their profile.", 'profile_update', null);
            notifyAllByRole('farmer', "Expert {$username} has updated their profile.", 'profile_update', null);
        } elseif ($userRole === 'admin') {
            notifyAllByRole('farmer', "Admin {$username} has updated their profile.", 'profile_update', null);
            notifyAllByRole('expert', "Admin {$username} has updated their profile.", 'profile_update', null);
        }

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

        $profileRoute = ($_SESSION['user_role'] ?? '') === 'farmer' ? '/my-profile' : '/profile';
        redirect($profileRoute);
    }
}
