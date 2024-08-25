<?php

namespace Camagru\Controller;

require_once __DIR__ . '/Core/functions.php'; 
class HomeController {
    // private string $viewBasePath;

    // public function __construct() {
    //     $this->viewBasePath = __DIR__ . '/../Presenter/views/';
    // }

    public function index() {
        // $this->render($this->viewBasePath . 'home/index.php', [
        //     'title' => 'Home Page'
        // ]);

        renderView('home/index.php', [
            'title' => 'Home Page'
        ]);
    }

// TODO: rendre la methode générale
    // private function render(string $viewPath, array $data = []) {
    //     extract($data);
    //     include $this->viewBasePath . 'shared/Layout.php';
    // }
}
