<?php

namespace Camagru\Core\Models;

use PDO;
use PDOException;
use Camagru\Core\Data\Connection;

class PostModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    // pas oublier de recuperer l'id de l'utilisateur pour l affichage de l'image
    public function ImageRegister($image) {
        
        try {
            $stmt = $this->db->prepare('INSERT INTO post (image, user_id) VALUES (:image, :user_id)');
            $stmt->bindValue(':image', $image, PDO::PARAM_LOB);
            $stmt->bindValue(':user_id', $_SESSION['user']['id'], PDO::PARAM_INT); // Assurez-vous que le user_id est valide
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Échec de l\'insertion : ' . $e->getMessage();
        }

    }
    public function getAllImages() 
    {
        $stmt = $this->db->query('SELECT * FROM post ORDER BY created_date DESC');
        $stmt->execute();
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($posts as &$post) {
            // Lire le contenu du stream
            if (is_resource($post['image'])) {
                $imageStream = stream_get_contents($post['image']);
                $post['image'] = base64_encode($imageStream);
            }
        }

        return $posts;
    }
}