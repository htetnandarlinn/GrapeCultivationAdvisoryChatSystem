<?php

namespace App\Presentation\Controllers\Admin;

use App\Application\UserManagement\RegisterUser\RegisterUserCommand;
use App\Application\UserManagement\RegisterUser\RegisterUserHandler;
use App\Domain\UserManagement\Entities\User;
use App\Domain\UserManagement\ValueObjects\Email;
use App\Domain\UserManagement\ValueObjects\UserType;
use App\Infrastructure\Mail\PHPMailerService;
use App\Infrastructure\Persistence\Repositories\UserRepository;
use App\Presentation\Views\AdminView;
use App\Shared\Exceptions\ValidationException;

class ExpertManagementController
{
    private UserRepository $userRepository;
    private RegisterUserHandler $registerHandler;

    public function __construct()
    {
        $this->userRepository = new UserRepository();

        $this->registerHandler = new RegisterUserHandler(
            $this->userRepository,
            new PHPMailerService()
        );
    }

    public function index(): void
    {
        $this->authorizeAdmin();

        if (!isset($_SESSION['admin_name'])) {
            $_SESSION['admin_name'] = $_SESSION['user']['username'] ?? 'Admin';
        }

        $message = $_SESSION['admin_message'] ?? null;
        unset($_SESSION['admin_message']);

        AdminView::render('admin/expertManagement', [
            'activePage' => 'experts',
            'experts' => $this->userRepository->findExperts(),
            'message' => $message,
        ]);
    }

    public function create(): void
    {
        $this->authorizeAdmin();

        AdminView::render('admin/createExpert', [
            'activePage' => 'experts',
            'mode' => 'create',
            'formAction' => '/admin/experts/store',
            'submitLabel' => 'Save Record',
        ]);
    }

    public function edit(): void
    {
        $this->authorizeAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            redirect('/admin/experts');
            return;
        }

        $expert = $this->userRepository->findById($id);

        if ($expert === null || $expert->getType()->getValue() !== 'expert') {
            $_SESSION['admin_message'] = 'Expert not found.';
            redirect('/admin/experts');
            return;
        }

        AdminView::render('admin/createExpert', [
            'activePage' => 'experts',
            'expert' => $expert,
            'mode' => 'edit',
            'formAction' => '/admin/experts/update',
            'submitLabel' => 'Update Expert',
        ]);
    }

    public function store(): void
    {
        $this->authorizeAdmin();

        try {
            $command = new RegisterUserCommand(
                id: null,
                username: trim($_POST['username']),
                email: trim($_POST['email']),
                phoneNumber: trim($_POST['phone']),
                address: trim($_POST['address']),
                passwordHash: password_hash($_POST['password'], PASSWORD_DEFAULT),
                type: UserType::expert()
            );

            $this->registerHandler->handle($command);

            $createdExpert = $this->userRepository->findByEmail($command->email);

            if ($createdExpert !== null) {
                $this->handleProfileImageUpload($_FILES['profile_image'] ?? null, $createdExpert);
            }

            $_SESSION['admin_message'] = 'Expert created successfully.';

            redirect('/admin/experts');
        } catch (ValidationException $e) {
            $_SESSION['errors'] = $e->getErrors();
            $_SESSION['old'] = $_POST;

            redirect('/admin/experts/create');
        } catch (\Throwable $e) {
            $_SESSION['errors'] = [
                'general' => $e->getMessage()
            ];

            $_SESSION['old'] = $_POST;

            redirect('/admin/experts/create');
        }
    }

    public function update(): void
    {
        $this->authorizeAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['admin_message'] = 'Invalid expert ID.';
            redirect('/admin/experts');
            return;
        }

        $expert = $this->userRepository->findById($id);

        if ($expert === null || $expert->getType()->getValue() !== 'expert') {
            $_SESSION['admin_message'] = 'Expert not found.';
            redirect('/admin/experts');
            return;
        }

        $username = trim((string) ($_POST['username'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $address = trim((string) ($_POST['address'] ?? ''));
        $password = trim((string) ($_POST['password'] ?? ''));

        $errors = [];

        if ($username === '') {
            $errors['username'] = 'Username is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'A valid email is required.';
        }

        $existingByEmail = $this->userRepository->findByEmail($email);
        if ($email !== '' && $existingByEmail !== null && $existingByEmail->getId() !== $expert->getId()) {
            $errors['email'] = 'Email address is already registered.';
        }

        $existingByUsername = $this->userRepository->findByUsername($username);
        if ($username !== '' && $existingByUsername !== null && $existingByUsername->getId() !== $expert->getId()) {
            $errors['username'] = 'Username is already taken.';
        }

        if ($password !== '' && strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/admin/experts/edit?id=' . $id);
            return;
        }

        try {
            $expert->setUsername($username);
            $expert->setEmail(new Email($email));
            $expert->setPhoneNumber($phone);
            $expert->setAddress($address);

            if ($password !== '') {
                $expert->setPasswordHash(password_hash($password, PASSWORD_DEFAULT));
            }

            $this->handleProfileImageUpload($_FILES['profile_image'] ?? null, $expert);

            $this->userRepository->update($expert);

            $_SESSION['admin_message'] = 'Expert updated successfully.';
        } catch (\Throwable $e) {
            $_SESSION['admin_message'] = 'Unable to update expert.';
        }

        redirect('/admin/experts');
    }

    public function delete(): void
    {
        $this->authorizeAdmin();

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['admin_message'] = 'Invalid expert ID.';
            redirect('/admin/experts');
            return;
        }

        try {
            $expert = $this->userRepository->findById($id);

            if ($expert === null) {
                $_SESSION['admin_message'] = 'Expert not found.';
                redirect('/admin/experts');
                return;
            }

            if ($expert->getType()->getValue() !== 'expert') {
                $_SESSION['admin_message'] = 'Only expert accounts can be deleted.';
                redirect('/admin/experts');
                return;
            }

            $this->userRepository->deleteById($id);

            $_SESSION['admin_message'] = 'Expert deleted successfully.';
        } catch (\Throwable $e) {
            $_SESSION['admin_message'] = 'Unable to delete expert.';
        }

        redirect('/admin/experts');
    }

    public function view(): void
    {
        $this->authorizeAdmin();

        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            redirect('/admin/experts');
            return;
        }

        $expert = $this->userRepository->findById($id);

        if (
            $expert === null ||
            $expert->getType()->getValue() !== 'expert'
        ) {
            $_SESSION['admin_message'] = 'Expert not found.';
            redirect('/admin/experts');
            return;
        }

        AdminView::render('admin/expert-view', [
            'activePage' => 'experts',
            'expert' => $expert,
        ]);
    }

    private function handleProfileImageUpload(?array $file, User $user): void
    {
        if (empty($file['name']) || !isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return;
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('profile_', true) . ($extension !== '' ? '.' . $extension : '');
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

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new \RuntimeException('Failed to upload profile image.');
        }

        $user->setProfileImage('/uploads/profile/' . $filename);
        $this->userRepository->update($user);
    }

    private function authorizeAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            redirect('/access-denied');
            exit;
        }
    }
}
