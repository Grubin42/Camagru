<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\LoginService;

class LoginController
{
    protected LoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    public function showLoginForm()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Login/index.php'
        ]);
    }

    public function authenticate($username, $password)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $this->loginService->login($username, $password);

        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: /');
            exit();
        } else {
            header('Location: /register');
            exit();
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: /login');
        exit();
    }
}