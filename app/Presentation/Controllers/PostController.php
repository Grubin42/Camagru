<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;

class PostController {
    private $PostService;

    public function __construct() {
        $this->PostService = new PostService();
    }



    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php'
        ]);
    }

    public function ImageRegister(string $imageContent) {
        $this->PostService->ImageRegister($imageContent);

        header('Location: /');
        exit; 
    }
}
