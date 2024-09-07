<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\LoginService;

class LoginController {
    private $Login;

    public function __construct() {
        $this->Login = new LoginService();
    }

    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Login/index.php'
        ]);
    }
    
    public function Login(string $username, string $password) {
        $user = $this->Login->Login($username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit();
        }
        else{
            $_SESSION['error_message'] = 'une erreur a eu lieu';
            header('Location: /login');
            exit();
        }

    }
}