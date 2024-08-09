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
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die('Connection failed: ' . $e->getMessage());
        }
    }
}