<?php

namespace Camagru\Controller;
use Camagru\Service\UserService;

class UserController {

    private string $viewBasePath;
    private UserService $userService;

    public function __construct() {
        $this->viewBasePath = __DIR__ . '/../Presenter/views/';
        $this->userService = new UserService();
    }

    public function index() {

        // TODO: make a service?
        $user = $this->userService->getUserById(1);

        $this->render($this->viewBasePath . 'settings/index.php', [
            'title' => 'Setting Page',
            'user' => $user
        ]);
    }
// TODO: rendre la methode générale
    private function render(string $viewPath, array $data = []) {
        extract($data);
        include $this->viewBasePath . 'shared/Layout.php';
    }
}