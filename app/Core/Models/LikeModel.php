<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class LikeModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function LikePost($postId, $userId) {
        // Vérifier si l'utilisateur a déjà liké ce post
        $stmt = $this->db->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
        $stmt->execute([
            ':post_id' => $postId,
            ':user_id' => $userId
        ]);
        
        $like = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$like) {
            // Si l'utilisateur n'a pas déjà liké, insérer le like
            $stmt = $this->db->prepare("INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)");
            $stmt->execute([
                ':post_id' => $postId,
                ':user_id' => $userId
            ]);
        } else {
            // Si l'utilisateur a déjà liké, on peut aussi envisager un mécanisme pour enlever le like
            $stmt = $this->db->prepare("DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id");
            $stmt->execute([
                ':post_id' => $postId,
                ':user_id' => $userId
            ]);
        }
    }
    public function getLikeCount($postId) {
        // Préparer la requête SQL pour compter les likes
        $stmt = $this->db->prepare("SELECT COUNT(*) as like_count FROM likes WHERE post_id = :post_id");
        $stmt->execute([
            ':post_id' => $postId
        ]);
    
        // Retourner le nombre de likes
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['like_count'] ?? 0;  // Retourner 0 si aucun like
    }
}