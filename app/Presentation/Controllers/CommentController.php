<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\CommentService;

class CommentController
{


    private $commentService;

    public function __construct() {
        // Assume autoloading for PostService or include manually
        $this->commentService = new  CommentService();
    }
    
    public function saveComment() {

        // session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'] ?? '';
            $comment = $_POST['comment'] ?? '';
            $postId = (int) $_POST['postId'] ?? '';

            $result = $this->commentService->saveComment($username, $comment, $postId);

        if ($result !== false) {
            // Comment saved successfully
            header('Location: /'); // Redirect to home page or wherever necessary
            exit;
        } else {
            // Handle any errors related to comment saving
            echo 'Error saving comment.';
        }
        }
    }

}