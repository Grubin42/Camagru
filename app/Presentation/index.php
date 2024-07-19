<?php
require_once __DIR__ . '/config.php';
function getDBConnection(): PDO {
    try {
        $dsn = 'pgsql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Connection failed: ' . $e->getMessage());
    }
}

try {
    $db = getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}
include 'Views/Shared/Layout.php'
?>
