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

    public function ImageRegister($imageContent) {
        try {
            // Insérez les données dans la base de données. Utilisez le `PDO::PARAM_LOB` pour indiquer qu'il s'agit d'un objet BLOB
            $stmt = $this->db->prepare('INSERT INTO post (imageContent, user_id) VALUES (:imageContent, :user_id)');
            $stmt->bindValue(':imageContent', $imageContent, PDO::PARAM_LOB);
            $stmt->bindValue(':user_id', 1, PDO::PARAM_INT); // Assurez-vous que le user_id est valide
            $stmt->execute();
        } catch (PDOException $e) {
            echo 'Échec de l\'insertion : ' . $e->getMessage();
        }
    }
}