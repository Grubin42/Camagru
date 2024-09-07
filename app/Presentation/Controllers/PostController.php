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

    public function ImageRegister(string $image) {
        $this->PostService->ImageRegister($image);

        header('Location: /');
        exit; 
    }

    public function SavePost()
    {
        $image = $_POST["image"];
        $sticker = $_POST["sticker"];
        $capturedImage = str_replace('data:image/png;base64,', '', $image);
        $capturedImage = base64_decode($capturedImage);
        $capturedstiker = str_replace('data:image/png;base64,', '', $sticker);
        $capturedsticker = base64_decode($capturedstiker);

        $this->PostService->MergeImage($capturedImage, $capturedsticker);
        header('Location: /');
        exit();
    }
}
