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
        // Récupérer l'image capturée et les stickers depuis le formulaire
        $capturedImage = $_POST['captured_image'] ?? null;
        $selectedStickersJson = $_POST['selected_stickers'] ?? null;
    
        if ($capturedImage && $selectedStickersJson) {
            // Décoder les stickers sélectionnés
            $selectedStickers = json_decode($selectedStickersJson, true);
            if (!is_array($selectedStickers)) {
                $this->validationService->addError('sticker', "Format des stickers sélectionnés invalide.");
                $_SESSION['errors'] = $this->validationService->getErrors();
                header('Location: /create-post');
                exit();
            }
    
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
    
            // Initialiser le contenu final de l'image fusionnée
            $mergedImage = $capturedImage;
    
            // Parcourir chaque sticker sélectionné et le fusionner avec l'image capturée
            foreach ($selectedStickers as $stickerUrl) {
                // Construire le chemin absolu du sticker
                $stickerName = basename($stickerUrl);
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
    
                // Fusionner l'image actuelle avec le nouveau sticker
                try {
                    $mergedImage = $this->postService->mergeImages($mergedImage, $stickerContent);
                } catch (\Exception $e) {
                    $this->validationService->addError('merge', "Erreur lors de la fusion des images : " . $e->getMessage());
                    $_SESSION['errors'] = $this->validationService->getErrors();
                    header('Location: /create-post');
                    exit();
                }
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