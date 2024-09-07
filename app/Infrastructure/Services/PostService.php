<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Post;
use Camagru\Core\Models\CommentModel;

class PostService
{

    private $postModel;

    public function __construct() {
        // Assume autoloading for PostService or include manually
        $this->postModel = new Post();
    }

    public function savePost($userId, $imagePath) {
        $this->postModel->savePost($userId, $imagePath);
    }

    public function showAllPost() {

        $posts =  $this->postModel->getLastPosts();
        $commentModel = new CommentModel();

        foreach ($posts as &$post) {
            $post['comments'] = $commentModel->getCommentsByPostId($post['id']);
        }

        return $posts;

    }


}