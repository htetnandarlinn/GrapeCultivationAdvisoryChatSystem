<?php

namespace App\Application\UserManagement\UpdateProfile;

class UpdateProfileRequestValidator
{
    public function validate(array $data, array $files): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors['username'] = "Username is required";
        }

        $email = trim((string) ($data['email'] ?? ''));

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Valid email is required";
        }

        if (!empty($data['password'])) {
            if (strlen($data['password']) < 6) {
                $errors['password'] = "Password must be at least 6 characters";
            }

            if ($data['password'] !== ($data['confirm_password'] ?? '')) {
                $errors['confirm_password'] = "Passwords do not match";
            }
        }

        if (!empty($files['avatar']['name'])) {
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];

            if (!in_array($files['avatar']['type'], $allowed)) {
                $errors['avatar'] = "Invalid image format";
            }
        }

        return $errors;
    }
}