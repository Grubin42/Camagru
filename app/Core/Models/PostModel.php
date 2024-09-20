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
    /*
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
    */
    public function getAllImages() :array
    {
        // Requête SQL pour récupérer les posts, leurs commentaires, et le nombre de likes
        $sql = "SELECT post.id as post_id, post.image, post.created_date, 
                       commentaire.id as comment_id, commentaire.commentaire, commentaire.username, commentaire.created_date as comment_date,
                       COUNT(likes.id) as like_count  -- Compter les likes
                FROM post
                LEFT JOIN commentaire ON post.id = commentaire.post_id
                LEFT JOIN likes ON post.id = likes.post_id  -- Joindre la table des likes
                GROUP BY post.id, commentaire.id  -- Grouper les résultats pour éviter les doublons
                ORDER BY post.created_date DESC";
        
        $stmt = $this->db->query($sql);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        // Organiser les résultats pour associer les commentaires et les likes à leurs posts
        $groupedPosts = [];
        foreach ($posts as $row) {
            $postId = $row['post_id'];
    
            // Si le post n'a pas encore été ajouté, on l'ajoute
            if (!isset($groupedPosts[$postId])) {
                $groupedPosts[$postId] = [
                    'id' => $row['post_id'],
                    'image' => base64_encode(stream_get_contents($row['image'])),
                    'created_date' => $row['created_date'],
                    'like_count' => $row['like_count'],  // Nombre de likes
                    'comments' => []  // Initialisation des commentaires
                ];
            }
    
            // Ajouter les commentaires
            if (!empty($row['comment_id'])) {
                $groupedPosts[$postId]['comments'][] = [
                    'comment_id' => $row['comment_id'],
                    'commentaire' => $row['commentaire'],
                    'username' => $row['username'],
                    'comment_date' => $row['comment_date']
                ];
            }
        }
    
        return array_values($groupedPosts);
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
    public function GetPostsPaginated($limit, $offset)
    {
        // Requête pour récupérer les posts et compter les likes
        $sql = "SELECT post.id as post_id, post.image, post.created_date, 
        COUNT(likes.id) as like_count  -- Compter les likes
        FROM post
        LEFT JOIN likes ON post.id = likes.post_id
        GROUP BY post.id
        ORDER BY post.created_date DESC
        LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ajouter les images et les commentaires
        $groupedPosts = [];
        foreach ($posts as $row) {
        $postId = $row['post_id'];

        // Ajouter les posts avec les images et les likes
        $groupedPosts[$postId] = [
            'id' => $postId,
            'image' => base64_encode(stream_get_contents($row['image'])),
            'created_date' => $row['created_date'],
            'like_count' => $row['like_count'],
            'comments' => []  // Les commentaires seront ajoutés séparément
            ];
        }

        // Récupérer les commentaires pour les posts affichés
        $this->addCommentsToPosts($groupedPosts);

        return array_values($groupedPosts);
    }
    private function addCommentsToPosts(array &$posts)
    {
        $postIds = array_keys($posts);

        if (empty($postIds)) {
            return;  // Ne pas continuer si aucun post n'est trouvé
        }

        $sql = "SELECT commentaire.id as comment_id, commentaire.commentaire, commentaire.username, commentaire.created_date as comment_date, commentaire.post_id
                FROM commentaire
                WHERE commentaire.post_id IN (" . implode(',', $postIds) . ")
                ORDER BY commentaire.created_date ASC";

        $stmt = $this->db->query($sql);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Associer les commentaires aux posts
        foreach ($comments as $comment) {
            $postId = $comment['post_id'];

            if (isset($posts[$postId])) {
                $posts[$postId]['comments'][] = [
                    'comment_id' => $comment['comment_id'],
                    'commentaire' => $comment['commentaire'],
                    'username' => $comment['username'],
                    'comment_date' => $comment['comment_date']
                ];
            }
        }
    }
    // Compter le nombre total de posts
    public function GetTotalPosts()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM post');
        return $stmt->fetchColumn();
    }
}