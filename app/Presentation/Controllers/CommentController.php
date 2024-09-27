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

        // Récupérer les données JSON envoyées par le fetch
        $data = json_decode(file_get_contents('php://input'), true);

        $postId = $data['post_id'] ?? null;
        $comment = $data['comment'] ?? null;
        $username = $_SESSION['user']['username'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if ($postId && $comment && $username) {
            // Ajouter le commentaire
            $this->commentService->addComment($postId, $comment, $username, $userId);

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