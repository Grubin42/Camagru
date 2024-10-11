<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;

class RegisterService
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function register(string $username, string $email, string $password): bool
    {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Invalid email format");
        }

        // Validate username (here, you can add more complexity checks)
        if (empty($username) || strlen($username) < 3) {
            throw new \Exception("Username must be at least 3 characters long.");
        }

        // Validate password complexity
        if (!isValidPassword($password)) {
            throw new \Exception("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number");
        }
        // Tu pourrais ajouter ici des vérifications supplémentaires avant d'enregistrer
        return $this->userModel->createUser($username, $email, $password);
    }
}

// Function to validate password complexity
function isValidPassword($password)
{
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{5,}$/', $password);
}