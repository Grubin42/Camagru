<?php

// Le fichier Connection.php
namespace Camagru\Core\Data;

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instance = null;

    public static function getDBConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                // L'utilisation de constantes définies dans config.php
                $dsn = 'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME;
                self::$instance = new PDO($dsn, DB_USER, DB_PASS);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Connection failed: ' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}