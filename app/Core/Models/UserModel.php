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
    public function getLastUser(): ?array
    {
        $stmt = $this->db->query('SELECT username, email FROM users ORDER BY id DESC LIMIT 1');
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
}