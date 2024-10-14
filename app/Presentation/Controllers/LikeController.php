<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\LikeService;

class LikeController
{
    protected $likeService;

    public function __construct()
    {
        $this->likeService = new LikeService();
    }

    public function toggleLike()
    {
        header('Content-Type: application/json'); // Ajouter ce header
        session_start();

        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Utilisateur non connecté']);
            return;
        }

        // Récupérer les données envoyées via JSON
        $data = json_decode(file_get_contents('php://input'), true);
        $postId = $data['post_id'] ?? null;
        $userId = $_SESSION['user']['id'] ?? null;

        if ($postId && $userId) {
            // Appel du service pour gérer le like
            $liked = $this->likeService->toggleLike($postId, $userId);
            $likeCount = $this->likeService->getLikeCount($postId);

            // Réponse JSON avec l'état du like et le nombre de likes
            echo json_encode([
                'like_count' => $likeCount,
                'liked' => $liked
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Données manquantes']);
        }
    }
}