<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    /**
     * Récupère le dernier utilisateur ajouté à la base de données.
     */
    public function GetUser(): ?array
    {
        // On récupère l'userId depuis la session
        $userId = $_SESSION['user']['id'];
        // Préparation de la requête SQL pour récupérer les likes correspondant à l'id utilisateur
        $stmt = $this->db->prepare('SELECT username, email FROM users WHERE id = :id');
        
        // Liaison de la variable :id à l'userId
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats sous forme de tableau associatif
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    public function Login(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function RegisterUser($username, $password, $email)
    {
        // Hash the password (utilisez password_hash() pour une sécurité accrue)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérez les données dans la base de données
        $stmt = $this->db->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $email]);
    }

    public function isUsernameTaken(string $username): bool {

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE username = :username');
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0; // Renvoie true si le nom d'utilisateur est pris
        
    }

    public function findUserByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Sauvegarder le token de réinitialisation
    public function storeResetToken($email, $token)
    {
        $expiration = date('Y-m-d H:i:s', strtotime('+1 hour')); // Token valide pendant 1 heure
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, reset_token_expiration = :expiration WHERE email = :email");
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiration', $expiration);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    }

    // Vérifier le token de réinitialisation
    public function verifyResetToken($token)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token AND reset_token_expiration > NOW()");
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function ResetPassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    
    // Met à jour le nom d'utilisateur
    public function UpdateUsername($userId, $username) {
        $stmt = $this->db->prepare('UPDATE users SET username = :username WHERE id = :id');
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Met à jour l'email
    public function UpdateEmail($userId, $email) {
        $stmt = $this->db->prepare('UPDATE users SET email = :email WHERE id = :id');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Met à jour le mot de passe
    public function UpdatePassword($userId, $hashedPassword) {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }
}