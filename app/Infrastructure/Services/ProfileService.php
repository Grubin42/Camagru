<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;
class ProfileService
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function GetUser(): ?array
    {
        return $this->userModel->GetUser();
    }

    public function UpdateProfile($username, $email, $password = null) {
        $userId = $_SESSION['user']['id'];
        // Si le nom d'utilisateur est fourni, on le met à jour
        if (!empty($username)) {
            $this->userModel->UpdateUsername($userId, $username);
        }

        // Si l'email est fourni, on le met à jour
        if (!empty($email)) {
            $this->userModel->UpdateEmail($userId, $email);
        }

        // Si un nouveau mot de passe est fourni, on le met à jour
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $this->userModel->UpdatePassword($userId, $hashedPassword);
        }
    }
}