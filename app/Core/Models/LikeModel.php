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

    /**
     * Récupère le dernier utilisateur ajouté à la base de données.
     */
    public function getLastUser(): ?array
    {
        $stmt = $this->db->query('SELECT username, email FROM users ORDER BY id DESC LIMIT 1');
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
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
}