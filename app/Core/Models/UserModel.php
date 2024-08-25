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

    public function RegisterUser($username, $password, $email)
    {
        // Hash the password (utilisez password_hash() pour une sécurité accrue)
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insérez les données dans la base de données
        $stmt = $this->db->prepare('INSERT INTO users (username, password, email) VALUES (?, ?, ?)');
        $stmt->execute([$username, $hashedPassword, $email]);
    }
}