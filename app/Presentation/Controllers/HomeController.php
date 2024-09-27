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
    public function AddComment(): void
    {
        $post_id = $_POST["post_id"];
        $comment = $_POST["comment"];
        $username = $_SESSION['user']['username'];

        $postOwner = $this->HomeService->GetPostOwner($post_id);

        $this->HomeService->AddComment($post_id, $comment);

        if ($postOwner['notif'] == TRUE){
            $this->EmailService->sendCommentNotification($postOwner['email'], $username, $postOwner['created_date']);
        }
    }
    public function likePost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postId = $_POST['post_id'];
            $userId = $_SESSION['user']['id'];  // Récupérer l'ID de l'utilisateur connecté
    
            // Appeler la méthode du service pour ajouter le like
            $this->HomeService->LikePost($postId, $userId);
        }
    }
}
