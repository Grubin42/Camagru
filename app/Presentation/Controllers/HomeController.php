<?php

namespace Presentation\Controllers;


use Camagru\Infrastructure\Services\HomeService;

use Camagru\Infrastructure\Services\PostService;

class HomeController {
   // private $Home_service;

   private $postService;

   public function __construct() {
         $this->postService = new PostService();
   }

    public function Index() {
        $posts = $this->postService->showAllPost(); 

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts,
        ]);
    }
}
