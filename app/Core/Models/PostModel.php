<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class PostModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function ImageRegister($imageContent)
    {
        // Insérez les données dans la base de données
        $stmt = $this->db->prepare('INSERT INTO post (imageContent, user_id) VALUES (?, ?)');
        $stmt->execute([null, 1]);
        // Autres actions après l'insertion réussie
    }
}