<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;

class RegisterService
{
    protected User $userModel;
    protected TokenService $tokenService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tokenService = new TokenService();
    }

    public function register(string $username, string $email, string $password): bool
    {
        // Vérifier si l'utilisateur existe déjà
        if ($this->userModel->findByUsername($username) || $this->userModel->findByEmail($email)) {
            return false;
        }

        if ($this->userModel->createUser($username, $email, $password)) {
            $user = $this->userModel->findByEmail($email);
            if ($user) {
                // Envoyer l'email de vérification
                return $this->tokenService->sendVerificationEmail($user['id'], $email);
            }
        }

        return false;
    }
}