<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\LoginService;
class LoginController {
   // private $Home_service;

   // public function __construct() {
   //     $this->Home_service = new HomeService();
   // }

    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Login/index.php'
        ]);
    }
    public function Login(string $username, string $password) {
        
        var_dump($username, $password);
    }
}