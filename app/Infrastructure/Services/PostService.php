<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Post;
use Camagru\Core\Models\Like;

class PostService
{
    protected $postModel;
    protected $likeModel;

    public function __construct()
    {
        $this->postModel = new Post();
        $this->likeModel = new Like();
    }

    public function getPostsPaginated(int $limit, int $offset, $userId = null): array
    {
        $posts = $this->postModel->getPostsPaginated($limit, $offset);

        foreach ($posts as &$post) {
            // Ajouter les likes
            $post['like_count'] = $this->likeModel->getLikeCount($post['id']);
            $post['liked_by_user'] = $userId ? $this->likeModel->userLikedPost($post['id'], $userId) : false;

            // Ajouter les commentaires
            $post['comment'] = $this->postModel->getCommentsForPost($post['id']);
        }

        return $posts;
    }

    public function getTotalPosts(): int
    {
        return $this->postModel->getTotalPosts();
    }

    public function mergeImages(string $capturedImage, string $sticker): string
    {
        // Créer les ressources d'image à partir des données binaires
        $capturedImageResource = imagecreatefromstring($capturedImage);
        $stickerResource = imagecreatefromstring($sticker);
    
        // Vérifier que les ressources sont valides
        if (!$capturedImageResource || !$stickerResource) {
            throw new \Exception("Erreur lors de la création des images.");
        }
    
        // Récupérer les dimensions de l'image capturée
        $capturedWidth = imagesx($capturedImageResource);
        $capturedHeight = imagesy($capturedImageResource);
    
        // Récupérer les dimensions du sticker
        $stickerWidth = imagesx($stickerResource);
        $stickerHeight = imagesy($stickerResource);
    
        // Définir la position du sticker (ici en haut à gauche, mais tu peux adapter)
        $xPosition = 0;
        $yPosition = 0;
    
        // Fusionner les images : d'abord l'image capturée, ensuite le sticker
        imagecopy($capturedImageResource, $stickerResource, $xPosition, $yPosition, 0, 0, $stickerWidth, $stickerHeight);
    
        // Sauvegarder l'image fusionnée dans une variable temporaire
        ob_start();
        imagepng($capturedImageResource);
        $mergedImageData = ob_get_clean();
    
        // Libérer la mémoire des ressources d'image
        imagedestroy($capturedImageResource);
        imagedestroy($stickerResource);
    
        // Retourner l'image fusionnée sous forme de chaîne binaire
        return $mergedImageData;
    }

    public function createPost($imageData)
    {
        $userId = $_SESSION['user']['id'] ?? null;
        $this->postModel->createPost($userId, $imageData);
    }
}