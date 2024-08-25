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
        // Tu pourrais ajouter ici des vérifications supplémentaires avant d'enregistrer
        return $this->userModel->createUser($username, $email, $password);
    }
}