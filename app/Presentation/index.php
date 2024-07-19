<?php

namespace Camagru;

require_once __DIR__ . '/config.php';
use Camagru\Core\Data\Connection;

try {
    $db = Connection::getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}
include 'Views/Shared/Layout.php'
?>
