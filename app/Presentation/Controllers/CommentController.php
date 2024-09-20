<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\CommentService;
use Camagru\Infrastructure\Services\MailService;

class CommentController
{
    protected $commentService;
    protected $mailService;

    public function __construct()
    {
        $this->commentService = new CommentService();
        $this->mailService = new MailService();
    }

    public function addComment()
    {
        if (!isset($_SESSION['user'])) {
            // Renvoyer une erreur 401 (Non autorisé)
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        $postId = $_POST['post_id'] ?? null;
        $comment = $_POST['comment'] ?? null;
        $username = $_SESSION['user']['username'] ?? null;

        if ($postId && $comment && $username) {
            // Ajouter le commentaire
            $this->commentService->addComment($postId, $comment, $username);

            // Récupérer l'email du propriétaire du post
            $postOwner = $this->commentService->getPostOwner($postId);
            if ($postOwner && $postOwner['notif']) {
                // Envoyer une notification par email au propriétaire du post
                $this->mailService->sendCommentNotification($postOwner['email'], $username, $postId);
            }

            // Renvoyer une réponse JSON avec les détails du nouveau commentaire
            echo json_encode([
                'username' => $username,
                'comment' => $comment
            ]);
            exit();
        } else {
            http_response_code(400); // Mauvaise requête
            echo json_encode(['error' => 'Données manquantes']);
            exit();
        }
    }
}