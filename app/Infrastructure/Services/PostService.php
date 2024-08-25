<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\PostModel as Post;

class PostService
{
    protected $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function getLastPosts(int $limit = 5): array
    {
        return $this->postModel->getLastPosts($limit);
    }
}