<?php

namespace Camagru\Core\Models;

use Camagru\Core\Data\Connection;
use PDO;

class Comment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    // Méthode pour mettre à jour les commentaires d'un utilisateur
    public function updateUsernameInComments($userId, $newUsername)
    {
        $stmt = $this->db->prepare('UPDATE comments SET username = :newUsername WHERE user_id = :userId');
        $stmt->bindParam(':newUsername', $newUsername, PDO::PARAM_STR);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function addComment(int $postId, string $comment, string $username, int $userId)
    {
        $stmt = $this->db->prepare('INSERT INTO comments (comment, username, post_id, user_id) VALUES (:comment, :username, :post_id, :user_id)');
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}