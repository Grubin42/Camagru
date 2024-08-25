<?php

namespace Camagru\Infrastructure\Services;

// use Camagru\Core\Models\PostModel as Post;

class PostService
{

    public function superimposeImages($capturedImageData, $overlayImagePath) {

        // Decode the base64-encoded captured image
        $capturedImage = $this->decodeBase64Image($capturedImageData);
        
        // // Validate overlay image path
        if (!file_exists($overlayImagePath)) {
            error_log("Overlay image does not exist: $overlayImagePath");
            return false;
        }

        // // Superimpose the images
        $result = $this->mergeImages($capturedImage, $overlayImagePath);
        echo $result;
    }

    private function decodeBase64Image($base64String) {
        list(, $data) = explode(',', $base64String);
        $data = base64_decode($data);

        // Create image resource from the decoded data
        $image = imagecreatefromstring($data);
        if (!$image) {
            error_log("Failed to create image from base64 data.");
            return false;
        }

        return $image;
    }

    private function mergeImages($capturedImage, $overlayImagePath) {
        // Load the overlay image
        $overlayImage = imagecreatefrompng($overlayImagePath);

        // Check if overlay loading was successful
        if (!$overlayImage) {
            error_log("Failed to load overlay image.");
            return false;
        }

        // Get dimensions and merge logic (same as before)
        $backgroundWidth = imagesx($capturedImage);
        $backgroundHeight = imagesy($capturedImage);

        $overlayWidth = imagesx($overlayImage);
        $overlayHeight = imagesy($overlayImage);

        // Center the overlay on the background image
        $x = ($backgroundWidth - $overlayWidth) / 2;
        $y = ($backgroundHeight - $overlayHeight) / 2;

        // Merge images
        imagecopy($capturedImage, $overlayImage, $x, $y, 0, 0, $overlayWidth, $overlayHeight);

        // Save the resulting image (or output it)
        $outputPath = 'uploads/result_image.png';
        imagepng($capturedImage, $outputPath);

        // Cleanup
        imagedestroy($capturedImage);
        imagedestroy($overlayImage);

        return $outputPath;
    }



}