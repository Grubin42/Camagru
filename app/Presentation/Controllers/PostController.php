<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;
use Camagru\Infrastructure\Services\CsrfService;
use Camagru\Infrastructure\Services\ValidationService;

class PostController
{
    protected PostService $postService;
    protected CsrfService $csrfService;
    protected ValidationService $validationService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->csrfService = new CsrfService();
        $this->validationService = new ValidationService();
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
        session_start(); // Assurez-vous que la session est démarrée
    
        // Récupérer l'image capturée et le sticker depuis le formulaire
        $capturedImage = $_POST['captured_image'] ?? null;
        $selectedStickerUrl = $_POST['selected_sticker'] ?? null;
    
        if ($capturedImage && $selectedStickerUrl) {
            // Valider l'image capturée
            if (!$this->validationService->validateImage($capturedImage)) {
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post'); // Rediriger vers le formulaire de création de post
                exit();
            }
    
            if (!$this->validationService->validateImageSize($capturedImage)) {
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
            // Nettoyer les données des images (base64 -> binaire)
            $capturedImage = preg_replace('/^data:image\/\w+;base64,/', '', $capturedImage);
            $capturedImage = base64_decode($capturedImage);
    
            // Vérifier si le décodage a réussi
            if ($capturedImage === false) {
                $this->validationService->addError('image', "Décodage de l'image capturée échoué.");
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
            // Construire le chemin absolu du sticker
            $stickerName = basename($selectedStickerUrl);
            $stickerPath = __DIR__ . '/../../Presentation/Assets/images/' . $stickerName;
    
            // Vérifier si le fichier existe
            if (!file_exists($stickerPath)) {
                $this->validationService->addError('sticker', "Le fichier sticker n'existe pas : " . htmlspecialchars($stickerPath));
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
            // Télécharger le sticker à partir de son chemin absolu
            $stickerContent = @file_get_contents($stickerPath);
            if ($stickerContent === false) {
                $this->validationService->addError('sticker', "Impossible de lire le fichier sticker.");
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
            // Appeler le service pour fusionner les images
            try {
                $mergedImage = $this->postService->mergeImages($capturedImage, $stickerContent);
            } catch (\Exception $e) {
                $this->validationService->addError('merge', "Erreur lors de la fusion des images : " . $e->getMessage());
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
            // Enregistrer l'image fusionnée en base de données
            $this->postService->createPost($mergedImage);
    
            // Rediriger vers la page des posts
            header('Location: /posts');
            exit();
        } else {
            $this->validationService->addError('general', "Données manquantes ou utilisateur non connecté.");
            $_SESSION['errors'] = $this->validationService->getErrors();
            header('Location: /create-post');
            exit();
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