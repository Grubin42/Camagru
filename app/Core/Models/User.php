<?php

namespace Camagru\Core\Models;

use PDO;
use Camagru\Core\Data\Connection;

class User
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

    /**
     * Récupère un utilisateur par son ID.
     *
     * @param int $id L'ID de l'utilisateur.
     * @return array|null Les informations de l'utilisateur, ou null si l'utilisateur n'existe pas.
     */
    public function getUserById(int $id): ?array
    {
        $sql = 'SELECT id, username, email, notif FROM users WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}