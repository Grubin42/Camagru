<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\CommentModel;
class HomeService
{
    private $CommentModel;

    public function __construct()
    {
        $this->CommentModel = new CommentModel();
    }

    public function AddComment($post_id, $comment)
    {
        return $this->CommentModel->SaveComment($post_id, $comment);
    }
}