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
        if (!$capturedImageResource) {
            throw new \Exception("Erreur lors de la création de l'image capturée.");
        }

        $stickerResource = imagecreatefromstring($sticker);
        if (!$stickerResource) {
            throw new \Exception("Erreur lors de la création du sticker.");
        }

        // Récupérer les dimensions de l'image capturée
        $capturedWidth = imagesx($capturedImageResource);
        $capturedHeight = imagesy($capturedImageResource);

        // Définir les dimensions maximales
        $maxWidth = 320;
        $maxHeight = 240;

        // Calculer le ratio de redimensionnement
        $widthRatio = $maxWidth / $capturedWidth;
        $heightRatio = $maxHeight / $capturedHeight;
        $ratio = min($widthRatio, $heightRatio, 1); // Ne pas agrandir si l'image est plus petite

        $newWidth = (int)($capturedWidth * $ratio);
        $newHeight = (int)($capturedHeight * $ratio);

        // Redimensionner l'image capturée si nécessaire
        if ($ratio < 1) {
            $resizedCapturedImage = imagecreatetruecolor($newWidth, $newHeight);
            // Maintenir la transparence pour les PNG et GIF
            imagealphablending($resizedCapturedImage, false);
            imagesavealpha($resizedCapturedImage, true);
            imagecopyresampled(
                $resizedCapturedImage,
                $capturedImageResource,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $capturedWidth, $capturedHeight
            );
            imagedestroy($capturedImageResource);
            $capturedImageResource = $resizedCapturedImage;
        }

        // Redimensionner le sticker à 100x100 pixels
        $stickerWidth = imagesx($stickerResource);
        $stickerHeight = imagesy($stickerResource);
        $desiredStickerWidth = 100;
        $desiredStickerHeight = 100;

        $resizedSticker = imagecreatetruecolor($desiredStickerWidth, $desiredStickerHeight);
        // Maintenir la transparence pour les PNG et GIF
        imagealphablending($resizedSticker, false);
        imagesavealpha($resizedSticker, true);
        imagecopyresampled(
            $resizedSticker,
            $stickerResource,
            0, 0, 0, 0,
            $desiredStickerWidth, $desiredStickerHeight,
            $stickerWidth, $stickerHeight
        );
        imagedestroy($stickerResource);

        // Position du sticker (exemple : coin supérieur gauche avec un décalage)
        $xPosition = 10;
        $yPosition = 10;

        // Fusionner le sticker sur l'image capturée
        $mergeSuccess = imagecopy(
            $capturedImageResource, // Image de destination
            $resizedSticker,        // Image source
            $xPosition,             // Position X dans l'image de destination
            $yPosition,             // Position Y dans l'image de destination
            0,                      // Position X dans l'image source
            0,                      // Position Y dans l'image source
            $desiredStickerWidth,   // Largeur de l'image source
            $desiredStickerHeight   // Hauteur de l'image source
        );

        if (!$mergeSuccess) {
            throw new \Exception("Erreur lors de la fusion des images.");
        }

        // Sauvegarder l'image fusionnée dans une variable temporaire
        ob_start();
        imagepng($capturedImageResource);
        $mergedImageData = ob_get_clean();

        // Libérer la mémoire des ressources d'image
        imagedestroy($capturedImageResource);
        imagedestroy($resizedSticker);

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