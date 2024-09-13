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

    public function SaveComment($post_id, $comment)
    {
        $username = $_SESSION['user']['username'];
        // Préparer la requête d'insertion du commentaire
        $sql = "INSERT INTO commentaire (commentaire, username, post_id, created_date) 
                VALUES (:commentaire, :username, :post_id, NOW())";
                
        // Préparer la requête
        $stmt = $this->db->prepare($sql);

        // Lier les paramètres à la requête
        $stmt->bindParam(':commentaire', $comment);
        $stmt->bindParam(':username', $username);  // Nom d'utilisateur, probablement récupéré depuis la session
        $stmt->bindParam(':post_id', $post_id);

        // Exécuter la requête
        return $stmt->execute();  // Retourner true si l'insertion réussit, false sinon
    }
}