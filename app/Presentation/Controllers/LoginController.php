<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\LoginService;
class LoginController {
   // private $Home_service;

   // public function __construct() {
   //     $this->Home_service = new HomeService();
   // }

   protected LoginService $loginService;

   public function __construct()
   {
       $this->loginService = new LoginService();
   }

   // renders the view
    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Login/index.php'
        ]);
    }

    // function responsible for authenticating the user
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
            $_SESSION['error_message'] = 'Identifiant ou mot de passe incorrect.';
            header('Location: /login');
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
