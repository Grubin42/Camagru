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
    public function RegisterUser(string $username, $password, $email)  
    {
        return $this->UserModel->RegisterUser($username, $password, $email);
    }
    public function isUsernameTaken(string $username): bool {
        return $this->UserModel->isUsernameTaken($username);
    }
    public function findUserByToken($token) {
        return $this->UserModel->findUserByToken($token);
    }
    
    public function verifyUser($userId) {
        return $this->UserModel->verifyUser($userId);
    }
}