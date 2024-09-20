<?php

namespace Camagru\Core\Models;

use Camagru\Core\Data\Connection;
use PDO;

class Like
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function checkLikeExists($postId, $userId): bool
    {
        $stmt = $this->db->prepare('SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addLike($postId, $userId)
    {
        $stmt = $this->db->prepare('INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function removeLike($postId, $userId)
    {
        $stmt = $this->db->prepare('DELETE FROM likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getLikeCount($postId): int
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as like_count FROM likes WHERE post_id = :post_id');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetch(PDO::FETCH_ASSOC)['like_count'];
    }

    public function userLikedPost($postId, $userId): bool
    {
        $stmt = $this->db->prepare('SELECT COUNT(*) as liked FROM likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool)$stmt->fetch(PDO::FETCH_ASSOC)['liked'];
    }
}