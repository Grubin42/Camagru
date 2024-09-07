<?php

namespace Camagru\Core\Models;

use Camagru\Core\Data\Connection;
use PDO;

class Post
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
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

    public function createPost($userId, $imageData)
    {
        // Debug pour voir si les données arrivent
        if (!$imageData) {
            echo "Erreur : l'image fusionnée est vide.";
            return;
        }
    
        // Insérer dans la base de données
        $stmt = $this->db->prepare('INSERT INTO post (image, user_id) VALUES (:image, :user_id)');
        $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    
        if (!$stmt->execute()) {
            // Debug pour l'insertion
            echo "Erreur lors de l'insertion en base de données.";
            var_dump($stmt->errorInfo());
            return;
        }
    }
}