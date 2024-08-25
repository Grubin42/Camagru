<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class RegisterController {
    private $Register;

    public function __construct() {
        $this->Register = new RegisterService();
    }


    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php'
        ]);
    }
    public function Register(string $username, $password, $email) {
        $this->Register->Register($username, $password, $email);
        header('Location: /login');
        exit; 
    }
}
