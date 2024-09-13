<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\CommentModel;
use Camagru\Core\Models\LikeModel;
class HomeService
{
    private $CommentModel;
    private $LikeModel;
    public function __construct()
    {
        $this->CommentModel = new CommentModel();
        $this->LikeModel = new LikeModel();
    }

    public function AddComment($post_id, $comment)
    {
        return $this->CommentModel->SaveComment($post_id, $comment);
    }
    public function LikePost($post_id, $userId)
    {
        return $this->LikeModel->LikePost($post_id, $userId);
    }
}