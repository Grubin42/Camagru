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

    public function login(string $username, string $password): ?array
    {
        $user = $this->userModel->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return null;
    }
}