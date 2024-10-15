<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;
use Camagru\Infrastructure\Services\CsrfService;

class PostController
{
    protected PostService $postService;
    protected CsrfService $csrfService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->csrfService = new CsrfService();
    }

    public function showCreatePostForm()
    {
        // Vérifier si l'utilisateur est connecté
        $userId = $_SESSION['user']['id'] ?? null;

        if ($userId) {
            // Récupérer les posts de l'utilisateur
            $userPosts = $this->postService->getPostsByUser($userId);
        } else {
            $userPosts = [];
        }

        // Générer le token CSRF
        $csrfToken = $this->csrfService->getToken();

        // Rendre la vue avec la page de création de post
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php',
            'userPosts' => $userPosts,
            'csrf_token' => $csrfToken // Passer le token à la vue
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

        $csrfToken = $this->csrfService->getToken();

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'csrf_token' => $csrfToken 
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
    
            // Vérifier si le décodage a réussi
            if ($capturedImage === false) {
                echo "Erreur : Décodage de l'image capturée échoué.";
                return;
            }
    
            // Construire le chemin absolu du sticker
            $stickerName = basename($selectedStickerUrl);
            $stickerPath = __DIR__ . '/../../Presentation/Assets/images/' . $stickerName;
    
            // Vérifier si le fichier existe
            if (!file_exists($stickerPath)) {
                echo "Erreur : Le fichier sticker n'existe pas : " . htmlspecialchars($stickerPath);
                return;
            }
    
            // Télécharger le sticker à partir de son chemin absolu
            $stickerContent = @file_get_contents($stickerPath);
            if ($stickerContent === false) {
                echo "Erreur : Impossible de lire le fichier sticker.";
                return;
            }
    
            // Appeler le service pour fusionner les images
            try {
                $mergedImage = $this->postService->mergeImages($capturedImage, $stickerContent);
            } catch (\Exception $e) {
                echo "Erreur lors de la fusion des images : " . $e->getMessage();
                return;
            }
    
            // Enregistrer l'image fusionnée en base de données
            $this->postService->createPost($mergedImage);
    
            // Rediriger vers la page des posts
            header('Location: /posts');
            exit();
        } else {
            echo "Erreur : données manquantes ou utilisateur non connecté.";
        }
    }

    public function deletePost()
    {
        // Vérifier si l'utilisateur est connecté
        $userId = $_SESSION['user']['id'] ?? null;
        if (!$userId) {
            header('Location: /login');
            exit();
        }

        // Vérifier le token CSRF
        $csrfToken = $_POST['_csrf_token'] ?? '';
        if (!$this->csrfService->validateToken($csrfToken)) {
            echo "Erreur : token CSRF invalide.";
            exit();
        }

        // Récupérer l'ID du post
        $postId = $_POST['post_id'] ?? null;
        if (!$postId) {
            echo "Erreur : ID du post manquant.";
            exit();
        }

        // Appeler le service pour supprimer le post
        $result = $this->postService->deletePost($postId, $userId);

        if ($result) {
            header('Location: /posts');
            exit();
        } else {
            echo "Erreur : impossible de supprimer le post.";
        }
    }
}