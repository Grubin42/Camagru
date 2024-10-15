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
    
        // Récupérer les dimensions du sticker
        $stickerWidth = imagesx($stickerResource);
        $stickerHeight = imagesy($stickerResource);
    
        // Dimensions souhaitées pour le sticker
        $desiredStickerWidth = 100; // Largeur souhaitée
        $desiredStickerHeight = 100; // Hauteur souhaitée
    
        // Position du sticker (exemple : coin supérieur gauche avec un décalage)
        $xPosition = 10;
        $yPosition = 10;
    
        // Fusionner les images avec redimensionnement du sticker
        $mergeSuccess = imagecopyresampled(
            $capturedImageResource, // Image de destination
            $stickerResource,       // Image source
            $xPosition,             // Position X dans l'image de destination
            $yPosition,             // Position Y dans l'image de destination
            0,                      // Position X dans l'image source
            0,                      // Position Y dans l'image source
            $desiredStickerWidth,   // Largeur souhaitée dans l'image de destination
            $desiredStickerHeight,  // Hauteur souhaitée dans l'image de destination
            $stickerWidth,          // Largeur originale de l'image source
            $stickerHeight          // Hauteur originale de l'image source
        );
    
        if (!$mergeSuccess) {
            throw new \Exception("Erreur lors de la fusion des images avec imagecopyresampled.");
        }
    
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

    public function getPostsByUser($userId): array
    {
        return $this->postModel->getPostsByUser($userId);
    }

    public function deletePost($postId, $userId)
    {
        return $this->postModel->deletePost($postId, $userId);
    }
}