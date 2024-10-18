<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\CommentService;
use Camagru\Infrastructure\Services\MailService;

class CommentController
{
    protected CommentService $commentService;
    protected MailService $mailService;

    public function __construct()
    {
        $this->commentService = new CommentService();
        $this->mailService = new MailService();
    }

    /**
     * Ajoute un commentaire à un post.
     */
    public function addComment()
    {
        header('Content-Type: application/json'); 
        
        if (!isset($_SESSION['user'])) {
            // Renvoyer une erreur 401 (Non autorisé)
            http_response_code(401);
            echo json_encode(['error' => 'Non autorisé']);
            exit();
        }

        // Récupérer les données JSON envoyées par le fetch
        $data = json_decode(file_get_contents('php://input'), true);

        $postId = isset($data['post_id']) ? (int)$data['post_id'] : null;
        $comment = isset($data['comment']) ? trim($data['comment']) : null;
        $username = $_SESSION['user']['username'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if ($postId && $comment && $username) {
            // Ajouter le commentaire via CommentService
            $result = $this->commentService->addComment($postId, $comment, $username, $userId);

            if ($result['success']) {
                // Récupérer l'email du propriétaire du post
                $postOwner = $this->commentService->getPostOwner($postId);
                if ($postOwner && $postOwner['notif']) {
                    // Envoyer une notification par email au propriétaire du post
                    $this->mailService->sendCommentNotification($postOwner['email'], $username, $postId);
                }

                // Renvoyer une réponse JSON avec les détails du nouveau commentaire
                echo json_encode([
                    'username' => htmlspecialchars($username),
                    'comment' => htmlspecialchars($comment)
                ]);
                exit();
            } else {
                // Renvoyer une réponse JSON avec les erreurs de validation
                http_response_code(400); // Bad Request
                echo json_encode(['errors' => $result['errors']]);
                exit();
            }
        } else {
            http_response_code(400); // Mauvaise requête
            echo json_encode(['error' => 'Données manquantes']);
            exit();
        }
    }
}