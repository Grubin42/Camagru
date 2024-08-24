<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\UserService;

class UserController {
    private $User;

    public function __construct() {
        $this->User = new UserService();
    }

    public function Index() {
        $userModel = new UserService() ;
        $lastUser = $userModel->getLastUser();
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Users/index.php',
            'user' => $lastUser
        ]);
    }
}