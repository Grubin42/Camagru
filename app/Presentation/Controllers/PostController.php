<?php

namespace Presentation\Controllers;

// use Camagru\Infrastructure\Services\PostService;

class PostController
{
    // protected $postService;

    // public function __construct()
    // {
    //     $this->postService = new PostService();
    // }

    public function showLastPosts()
    {
        // $posts = $this->postService->getLastPosts();
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php',
        ]);
    }
}