<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class CommentModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function saveComment($username, $comment, $postId) {
        // Prepare the SQL statement
        $query = "INSERT INTO commentaire (commentaire, username, post_id) VALUES (:comment, :username, :post_id)";

        // Prepare the statement
        $stmt = $this->db->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);

        // echo $comment;
        // echo $username;
        // echo $postId;

        // Execute the query
        if ($stmt->execute()) {
            return $this->db->lastInsertId(); // Return the ID of the newly inserted comment if needed
        } else {
            // Handle errors on execution
            error_log('Failed to save comment: ' . implode(', ', $stmt->errorInfo()));
            return false; // Return false if it fails
        }
       
    }

    public function getCommentsByPostId($postId) {
        $query = "SELECT username, commentaire, created_date FROM commentaire WHERE post_id = :post_id ORDER BY created_date DESC";
        
        // Prepare and execute the statement
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch comments as an associative array
    }

}
