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

    public function authenticate()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit();
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            $this->setErrorAndRedirect('Veuillez remplir tous les champs.');
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $user = $this->loginService->login($username, $password);

        if ($user) {
            session_regenerate_id(true); // Sécurité : régénère l'ID de session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'notif' => $user['notif']
            ];
            header('Location: /');
            exit();
        } else {
            $this->setErrorAndRedirect('Identifiants invalides ou compte non vérifié.');
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy();
        header('Location: /');
        exit();
    }

    private function setErrorAndRedirect(string $message)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['error_message'] = $message;
        header('Location: /login');
        exit();
    }
}