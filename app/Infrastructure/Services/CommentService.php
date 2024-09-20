<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Comment;
use Camagru\Core\Models\Post;

class CommentService
{
    protected $commentModel;
    protected $postModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
        $this->postModel = new Post();
    }

    public function addComment(int $postId, string $comment, string $username)
    {
        return $this->commentModel->addComment($postId, $comment, $username);
    }

    public function getPostOwner(int $postId)
    {
        return $this->postModel->getPostOwnerById($postId);
    }
}