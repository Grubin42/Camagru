<?php

namespace Camagru\Core\Data;

use PDO;
use PDOException;

class Connection
{
    public static function getDBConnection(): PDO {
        try {
            $dsn = 'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $pdo = new PDO($dsn, DB_USER, DB_PASS);

            // Configurations supplémentaires pour PDO
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

            return $pdo;
        } catch (PDOException $e) {
            // Gestion d'erreur améliorée
            throw new \RuntimeException('Connection failed: ' . $e->getMessage());
        }
    }
}