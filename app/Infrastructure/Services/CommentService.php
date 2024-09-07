<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\CommentModel;

class CommentService
{

    private $commentModel;

    public function __construct() {
        // Assume autoloading for PostService or include manually
        $this->commentModel = new CommentModel();
    }

    public function saveComment($username, $comment, $postId) {

        return $this->commentModel->saveComment($username, $comment, $postId);
    }

    public function getCommentsByPostId($postId) {
        return $this->commentModel->getCommentsByPostId($postId);
    }


}