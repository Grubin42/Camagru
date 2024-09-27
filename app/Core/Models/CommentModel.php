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
        // Récupérer l'username et l'user_id depuis la session
        $username = $_SESSION['user']['username'];
        $user_id = $_SESSION['user']['id']; // Assurez-vous que l'user_id est bien stocké dans la session
        
        // Préparer la requête d'insertion du commentaire avec user_id
        $sql = "INSERT INTO commentaire (commentaire, username, post_id, user_id, created_date) 
                VALUES (:commentaire, :username, :post_id, :user_id, NOW())";
        
        // Préparer la requête
        $stmt = $this->db->prepare($sql);
        
        // Lier les paramètres à la requête
        $stmt->bindParam(':commentaire', $comment);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':post_id', $post_id);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); // Liaison du user_id
        
        // Exécuter la requête
        return $stmt->execute(); // Retourner true si l'insertion réussit, false sinon
    }
}