<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;

class PostController
{

    private $postService;

    public function __construct() {
        // Assume autoloading for PostService or include manually
        $this->postService = new PostService();
    }

    public function handleFormSubmit() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $capturedImageData = $_POST['captured_image'] ?? '';
            $overlayImage = $_POST['overlay_image'] ?? '';

            // Log the form content to the server-side log
            error_log("Captured Image Data: $capturedImageData");
            error_log("Overlay Image: $overlayImage");

            // Call the service to process the images
            $result = $this->postService->superimposeImages($capturedImageData, $overlayImage);

            if ($result) {
                // You can redirect or render the output image
                echo "Image processed successfully.";
            } else {
                echo "Failed to process image.";
            }
        }
    }

    public function createPost()
    {
        // $posts = $this->postService->getLastPosts();
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php',
        ]);
    }
}