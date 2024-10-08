<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;

class LoginService
{
    protected User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Authentifie l'utilisateur en vérifiant le username et le mot de passe.
     *
     * @param string $username
     * @param string $password
     * @return array|null
     */
    public function login(string $username, string $password): ?array
    {
        // Utilise la méthode findByUsernameAndVerified pour s'assurer que l'utilisateur est vérifié
        $user = $this->userModel->findByUsernameAndVerified($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}