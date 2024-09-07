<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;
class HomeController {
    private $Postservice;

    public function __construct() {
        $this->Postservice = new PostService();
    }
    public function Index()
    {
        $posts = $this->Postservice->GetAllImage();

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts
        ]);
    }
}
