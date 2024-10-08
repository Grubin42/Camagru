<?php

namespace Camagru\Core\Models;

use Camagru\Core\Data\Connection;
use PDO;

class User
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username LIMIT 1');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function createUser(string $username, string $email, string $password): bool
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $verified = 0; // Utiliser 0 pour FALSE

        $stmt = $this->db->prepare('INSERT INTO users (username, email, password, verified) VALUES (:username, :email, :password, :verified)');
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password' => $hashedPassword,
            'verified' => $verified
        ]);
    }
    
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updatePassword(int $userId, string $newPassword): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET password = :password WHERE id = :id');
        return $stmt->execute(['password' => password_hash($newPassword, PASSWORD_DEFAULT), 'id' => $userId]);
    }

    public function updateUserProfile($userId, $username, $email, $password = null, $notif)
    {
        // Gérer la mise à jour du mot de passe uniquement si un nouveau mot de passe est fourni
        if ($password) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email, password = :password, notif = :notif WHERE id = :id');
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            $stmt = $this->db->prepare('UPDATE users SET username = :username, email = :email, notif = :notif WHERE id = :id');
        }
    
        // Lier les autres paramètres
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':notif', $notif, PDO::PARAM_BOOL); // Ici, on s'assure que notif est un booléen
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    
        $stmt->execute();
    }

    public function setVerified(int $userId): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET verified = TRUE WHERE id = :id');
        return $stmt->execute(['id' => $userId]);
    }

    /**
     * Recherche un utilisateur par username uniquement s'il est vérifié.
     *
     * @param string $username
     * @return array|null
     */
    public function findByUsernameAndVerified(string $username): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username AND verified = TRUE LIMIT 1');
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}