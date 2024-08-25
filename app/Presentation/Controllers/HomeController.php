<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;

class HomeController
{
    protected $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function showHomePage()
    {
        $posts = $this->postService->getLastPosts();
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts
        ]);
    }
}