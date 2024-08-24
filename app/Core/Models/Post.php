<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    /**
     * Retrieve all posts with their associated comments.
     */
    public function getAllPostsWithComments(): array
    {
        $sql = "
            SELECT p.id as post_id, p.image, p.created_at, u.username,
                   c.id as comment_id, c.content as comment_content, c.created_at as comment_created_at, c.username as comment_username
            FROM post p
            JOIN users u ON p.user_id = u.id
            LEFT JOIN comment c ON p.id = c.post_id
            ORDER BY p.created_at DESC, c.created_at ASC
        ";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $posts = [];
        foreach ($results as $row) {
            $postId = $row['post_id'];

            if (!isset($posts[$postId])) {
                $posts[$postId] = [
                    'post_id' => $row['post_id'],
                    'image' => $row['image'],
                    'created_at' => $row['created_at'],
                    'username' => $row['username'],
                    'comments' => [],
                ];
            }

            if ($row['comment_id'] !== null) {
                $posts[$postId]['comments'][] = [
                    'comment_id' => $row['comment_id'],
                    'content' => $row['comment_content'],
                    'created_at' => $row['comment_created_at'],
                    'username' => $row['comment_username']
                ];
            }
        }

        return array_values($posts); // Re-indexing array to have a numeric index
    }

}
