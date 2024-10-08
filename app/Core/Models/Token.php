<?php

namespace Camagru\Core\Models;

use Camagru\Core\Data\Connection;
use PDO;

class Token
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getDBConnection();
    }

    public function createToken(int $userId, string $token, string $expiresAt, string $type)
    {
        $stmt = $this->db->prepare('INSERT INTO tokens (user_id, token, expires_at, type) VALUES (:user_id, :token, :expires_at, :type)');
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        $stmt->bindParam(':expires_at', $expiresAt, PDO::PARAM_STR);
        $stmt->bindParam(':type', $type, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function findByTokenAndType(string $token, string $type): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM tokens WHERE token = :token AND type = :type AND expires_at > NOW()');
        $stmt->execute(['token' => $token, 'type' => $type]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function deleteByToken(string $token)
    {
        $stmt = $this->db->prepare('DELETE FROM tokens WHERE token = :token');
        $stmt->bindParam(':token', $token, PDO::PARAM_STR);
        return $stmt->execute();
    }
}