<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;
use Camagru\Infrastructure\Services\HomeService;
class HomeController {
    private $Postservice;
    private $HomeService;

    public function __construct() {
        $this->Postservice = new PostService();
        $this->HomeService = new HomeService();
    }
    public function Index()
    {
        $posts = $this->Postservice->GetAllImage();

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts
        ]);
    }

    public function AddComment()
    {
        $post_id = $_POST["post_id"];
        $comment = $_POST["comment"];
        $this->HomeService->AddComment($post_id, $comment);
    }
}
