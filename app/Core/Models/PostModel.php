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
    public function getAllImages() :array
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
    public function saveImageToDatabase($imageData): bool {
        try {
            $userId = $_SESSION['user']['id'];
    
            // Préparer la requête pour insérer les données binaires
            $stmt = $this->db->prepare('INSERT INTO post (image, user_id) VALUES (:image, :user_id)');
            $stmt->bindParam(':image', $imageData, PDO::PARAM_LOB); // Paramètre pour données LOB (binaire)
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    
            // Exécuter la requête
            if ($stmt->execute()) {
                return true;
            } else {
                $errorInfo = $stmt->errorInfo();
                echo "Erreur lors de l'insertion en base de données : " . $errorInfo[2];
                return false;
            }
        } catch (PDOException $e) {
            echo "Exception lors de l'insertion : " . $e->getMessage();
            return false;
        }
    }

    public function getImagesByUserId() : array
    {
        $userId = $_SESSION['user']['id'];
        // Préparer la requête SQL avec une condition WHERE pour l'user_id
        $stmt = $this->db->prepare('SELECT * FROM post WHERE user_id = :userId');
        
        // Exécuter la requête en passant l'id de l'utilisateur comme paramètre
        $stmt->execute(['userId' => $userId]);

        // Récupérer les posts associés à cet utilisateur
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Boucle pour convertir les images en base64
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