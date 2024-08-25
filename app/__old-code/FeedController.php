<?php

namespace Camagru\Controller;

class FeedController {
    private string $viewBasePath;

    public function __construct() {
        $this->viewBasePath = __DIR__ . '/../Presenter/views/';
    }

    public function index() {

        // TODO: make a service?
        $this->render($this->viewBasePath . 'feed/index.php', [
            'title' => 'Feed Page'
        ]);
    }
// TODO: rendre la methode générale
    private function render(string $viewPath, array $data = []) {
        extract($data);
        include $this->viewBasePath . 'shared/Layout.php';
    }
}
