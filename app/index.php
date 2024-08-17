<?php

namespace Camagru;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Core/Data/Connection.php';

use PDO;
use Exception;
use Camagru\Core\Data\Connection;

try {
    $db = Connection::getDBConnection();
} catch (Exception $e) {
    // TODO: error db connection
}

$request = $_SERVER['REQUEST_URI'];
$viewDir = '/Presenter/views/';

switch ($request) {
    case '':
    case '/':
        require __DIR__ . $viewDir . 'home/index.php';
        break;

    case '/settings':
        require __DIR__ . $viewDir . 'settings/index.php';
        break;

    default:
        http_response_code(404);
        require __DIR__ . $viewDir . 'error/404.php';
}

?>
