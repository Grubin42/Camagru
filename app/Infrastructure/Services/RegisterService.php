<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;
class RegisterService
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function getLastUser(): ?array
    {
        return $this->UserModel->getLastUser();
    }
    public function Register(string $username, $password, $email): ?array
    {
        return $this->UserModel->RegisterUser($username, $password, $email);
    }
}