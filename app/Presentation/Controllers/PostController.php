<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;

class PostController
{
    protected $postService;

    public function __construct()
    {
        $this->postService = new PostService();
    }

    public function showCreatePostForm()
    {
        // Rendre la vue avec la page de création de post
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php'
        ]);
    }

    public function savePost()
    {
        // Récupérer l'image capturée et le sticker depuis le formulaire
        $capturedImage = $_POST['captured_image'] ?? null;
        $selectedStickerUrl = $_POST['selected_sticker'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;
    
        if ($capturedImage && $selectedStickerUrl && $userId) {
            // Nettoyer les données des images (base64 -> binaire)
            $capturedImage = str_replace('data:image/png;base64,', '', $capturedImage);
            $capturedImage = base64_decode($capturedImage);
    
            // Télécharger le sticker à partir de son URL
            $stickerContent = file_get_contents($selectedStickerUrl);
            if ($stickerContent === false) {
                echo "Erreur : impossible de télécharger le sticker.";
                return;
            }
    
            // Appeler le service pour fusionner les images
            $mergedImage = $this->postService->mergeImages($capturedImage, $stickerContent);
    
            // Enregistrer l'image fusionnée en base de données
            $this->postService->createPost($userId, $mergedImage);
    
            // Rediriger vers la page des posts
            header('Location: /posts');
            exit();
        } else {
            echo "Erreur : données manquantes ou utilisateur non connecté.";
        }
    }
}