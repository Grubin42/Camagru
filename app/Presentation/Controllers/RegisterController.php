<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class RegisterController
{
    protected RegisterService $registerService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
    }

    public function showRegisterForm()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php'
        ]);
    }

    public function register()
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if ($this->registerService->register($username, $email, $password)) {
            header('Location: /login');
            exit();
        } else {
            header('Location: /register');
            exit();
        }
    }
}