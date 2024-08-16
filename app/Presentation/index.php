<?php

namespace Camagru;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../Core/Data/Connection.php';

use PDO;
use Exception;
use Camagru\Core\Data\Connection;

try {
    $db = Connection::getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}

// function renderView($view, $data) {
//     extract($data);
//     include $view;
// }

// renderView(__DIR__ . '/Views/Shared/Layout.php', ['users' => $users]);
include __DIR__ . '/Views/Shared/Layout.php';
?>
