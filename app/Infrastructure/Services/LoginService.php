<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;
class LoginService
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }
    public function Login(string $username, string $password): ?array
    {
        $user = $this->UserModel->Login($username);
        
        if ($user && password_verify($password, $user['password'])){
            return $user;
        }
        return null;
    }
}