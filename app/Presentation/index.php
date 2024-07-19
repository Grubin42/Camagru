<?php
use PDO;
use Exception;
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../Core/Data/Connection.php';

use app\Core\Data\Connection;

try {
    ini_set('display_errors', 1);
    $db = Connection::getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}
include 'Views/Shared/Layout.php'
?>
