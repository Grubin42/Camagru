<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;
use Camagru\Infrastructure\Services\HomeService;
use Camagru\Infrastructure\Services\EmailService;
class HomeController {
    private $Postservice;
    private $HomeService;
    private $EmailService;

    public function __construct() {
        $this->Postservice = new PostService();
        $this->HomeService = new HomeService();
        $this->EmailService = new EmailService();
    }

    public function Index()
    {
        
        // Nombre de posts par page
        $postsPerPage = 5;

        // Page actuelle (par défaut 1 si non spécifié)
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calcul de l'offset
        $offset = ($currentPage - 1) * $postsPerPage;

        // Récupérer les posts limités et le nombre total de posts
        $posts = $this->Postservice->GetPostsPaginated($postsPerPage, $offset);
        $totalPosts = $this->Postservice->GetTotalPosts();

        // Calcul du nombre total de pages
        $totalPages = ceil($totalPosts / $postsPerPage);

        // Passer les données à la vue
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
        exit();
    }
    public function AddComment()
    {
        // Vérifier si la requête est bien en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_id = $_POST["post_id"];
            $comment = trim($_POST["comment"]);
            $username = $_SESSION['user']['username'];
    
            // Récupérer les informations du propriétaire du post
            $postOwner = $this->HomeService->GetPostOwner($post_id);
    
            // Ajouter le commentaire à la base de données
            $this->HomeService->AddComment($post_id, $comment);
    
            // Si le propriétaire du post a activé les notifications, envoyer un email
            if ($postOwner['notif'] == TRUE) {
                $this->EmailService->sendCommentNotification($postOwner['email'], $username, $postOwner['created_date']);
            }
    
            // Retourner une réponse JSON avec le nouveau commentaire
            echo json_encode([
                'success' => true,
                'username' => htmlspecialchars($username),
                'comment' => htmlspecialchars($comment)
            ]);
        } else {
            // Si la requête n'est pas POST, retourner une erreur JSON
            echo json_encode([
                'success' => false,
                'message' => 'Méthode de requête invalide'
            ]);
        }
        exit();
    }
    public function likePost() {

        // Vérifie que la requête est bien POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode([
                'success' => false,
                'message' => 'Méthode de requête non valide.'
            ]);
            exit();
        }

        // Récupérer l'ID du post et l'ID de l'utilisateur connecté
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user']['id'];

        // Appelle le service pour ajouter le like
        $this->HomeService->LikePost($postId, $userId);

        // Récupérer le nouveau nombre de likes après l'ajout du like
        $likeCount = $this->HomeService->GetLikeCount($postId);

        // Retourner une réponse JSON avec le nouveau nombre de likes
        echo json_encode([
            'success' => true,
            'message' => 'Like ajouté avec succès.',
            'likes' => $likeCount
        ]);
        exit();
    }
}
