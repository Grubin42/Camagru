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

    public function getAllPosts() {
        $query = "SELECT id, image, user_id, created_date FROM post ORDER BY created_date DESC"; // Adjust to your desired order
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all posts as an associative array //TODO: check what is means
    }


    public function getLastPosts(int $limit = 5): array
    {
        $stmt = $this->db->query('SELECT * FROM post ORDER BY created_date DESC LIMIT ' . $limit);
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Parcourir les posts pour convertir l'image en base64
        foreach ($posts as &$post) {
            // Lire le contenu du stream
            if (is_resource($post['image'])) {
                $imageStream = stream_get_contents($post['image']);
                $post['image'] = base64_encode($imageStream);
            }
        }
    
        return $posts;
    }

    public function savePost($userId, $imagePath) {
        // Read the image file content
        $imageContent = file_get_contents($imagePath);

        //TODO: try catch
                $stmt = $this->db->prepare('INSERT INTO post (image, user_id) VALUES (:image, :user_id)');
                $stmt->bindValue(':image', $imageContent, PDO::PARAM_LOB);
                $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT); // Assurez-vous que le user_id est valide
                $stmt->execute();
                return $this->db->lastInsertId();
        }



}
