<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\Post;

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

    public function createPost($imageData, $userId)
    {
        $this->postModel->createPost($imageData, $userId);
    }
}