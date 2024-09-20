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

    public function showPosts($page = 1)
    {
        $postsPerPage = 5;
        $offset = ($page - 1) * $postsPerPage;

        // Obtenir les posts avec pagination
        $posts = $this->postService->getPostsPaginated($postsPerPage, $offset);

        // Obtenir le nombre total de posts pour calculer le nombre de pages
        $totalPosts = $this->postService->getTotalPosts();
        $totalPages = ceil($totalPosts / $postsPerPage);

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }

    public function savePost()
    {
        // Récupérer l'image capturée et le sticker depuis le formulaire
        $capturedImage = $_POST['captured_image'] ?? null;
        $selectedStickerUrl = $_POST['selected_sticker'] ?? null;
    
        if ($capturedImage && $selectedStickerUrl) {
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
            $this->postService->createPost($mergedImage);
    
            // Rediriger vers la page des posts
            header('Location: /');
            exit();
        } else {
            echo "Erreur : données manquantes ou utilisateur non connecté.";
        }
    }
}