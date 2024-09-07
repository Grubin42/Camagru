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

        session_start();

        // TODO: make a service

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


            // Create output file path for the combined image.
            $outputImagePath = '/Presentation/Assets/save/output_image.png'; // Ensure this directory is writablec

            // Return the output image path as JSON
            header('Content-Type: application/json');
            echo json_encode(['imagePath' => 'output_image.png']);
            
            // Get the URL of the newly created image

            // Redirect to the /publish page with the image path as a query parameter
            $_SESSION['imagePath'] = 'output_image.png';

            header('Location: /publish');
            exit; 
        }
    }

    public function confirmationPublishPost() {
        // renderView(__DIR__ . '/../Views/Shared/Layout.php', [
        //     'view' => __DIR__ . '/../Views/Publish/index.php',
        //     // 'supperposedImagePath' => $_POST['imagePath'] ?? '',
        // ]);

        session_start(); 

        $imagePath = $_SESSION['imagePath'] ?? ''; 

         // Optionally, unset the session variable if you don't need it anymore
        unset($_SESSION['imagePath']); 

        if (!$imagePath) {
            // Handle the case where the image path is not available
            echo 'No image available for publication.';
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // if(!isset($_POST['imagePath'])) {
            //     die('No image path provided.'); //TODO: check `die` function
            // }
            // Publish the post
            $this->postService->savePost(1, $imagePath);


            // Redirect to the home page
            header('Location: /home');
            exit;
        }
    }
  

    public function publishPost() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Publish/index.php',
            // 'supperposedImagePath' => $_POST['imagePath'] ?? '',
        ]);
    }
    

    public function createPost()
    {
        // $posts = $this->postService->getLastPosts();
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Create/index.php',
        ]);
    }
}