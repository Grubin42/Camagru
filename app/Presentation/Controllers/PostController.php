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
            $overlayImagePath = $_POST['overlay_image'] ?? '';

             // Debug: Print received base64 data
        error_log("Captured Image Data: " . substr($capturedImageData, 0, 100) . "..."); // Print first 100 characters
    
            // Decode the base64 image
            $capturedImageData = str_replace('data:image/png;base64,', '', $capturedImageData);
            $capturedImageData = str_replace(' ', '+', $capturedImageData);
            $decodedImage = base64_decode($capturedImageData);
            
            // Create an image resource from the decoded data
            $capturedImage = imagecreatefromstring($decodedImage);
            if ($capturedImage === false) {
                die('Error creating image from string');
            }
    
            // Create an image resource from the overlay image
            $overlayImage = imagecreatefrompng($overlayImagePath); // Change to imagecreatefromjpeg if JPEG
            if ($overlayImage === false) {
                die('Error creating image from overlay image');
            }
    
            // Get dimensions of the overlay image
            $overlayWidth = imagesx($overlayImage);
            $overlayHeight = imagesy($overlayImage);
    
            // Overlay the images (you may adjust the position as needed)
            imagecopy($capturedImage, $overlayImage, 0, 0, 0, 0, $overlayWidth, $overlayHeight);
    
            // Output the image (you may save it to the server instead)
            // header('Content-Type: image/png');
            imagepng($capturedImage, 'output_image.png');
            
            // Free up memory
            imagedestroy($capturedImage);
            imagedestroy($overlayImage);

            // Return the output image path as JSON
            header('Content-Type: application/json');
            echo json_encode(['imagePath' => 'output_image.png']);
            exit; // Important to stop further output
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