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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'] ?? '';
            $comment = $_POST['comment'] ?? '';
            $postId = (int) $_POST['postId'] ?? '';

            $result = $this->commentService->saveComment($username, $comment, $postId);

        if ($result !== false) {
            header('Location: /'); // Redirect to home page or wherever necessary
            exit;
        } else {
            echo 'Error saving comment.';   
        }
        }
    }

}