<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Like;

class LikeService
{
    protected $likeModel;

    public function __construct()
    {
        $this->likeModel = new Like();
    }

    public function toggleLike($postId, $userId)
    {
        // Vérifier si un like existe
        if ($this->likeModel->checkLikeExists($postId, $userId)) {
            // Si le like existe, le supprimer (dislike)
            $this->likeModel->removeLike($postId, $userId);
        } else {
            // Sinon, ajouter un nouveau like
            $this->likeModel->addLike($postId, $userId);
        }
    }

    // Récupère le nombre de likes pour un post
    public function getLikeCount(int $postId): int
    {
        return $this->likeModel->getLikeCount($postId);
    }
}