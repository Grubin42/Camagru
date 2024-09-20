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

    public function addComment(int $postId, string $comment, string $username)
    {
        $stmt = $this->db->prepare('INSERT INTO commentaire (commentaire, username, post_id) VALUES (:commentaire, :username, :post_id)');
        $stmt->bindParam(':commentaire', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}