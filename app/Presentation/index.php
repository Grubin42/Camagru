<?php

namespace Camagru;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../Core/Data/Connection.php';

use PDO;
use Exception;
use Camagru\Core\Data\Connection;

try {
    $db = Connection::getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users ORDER BY id DESC LIMIT 1');
    $lastUser = $stmt->fetch(PDO::FETCH_ASSOC);
    var_dump($lastUser);
    //$users = $lastUser ? [$lastUser] : []; // Assigner $lastUser à $users si trouvé
} catch (Exception $e) {
    $lastUser = null;
}

// Fonction pour rendre la vue
function renderView($view, $data = []) {
    extract($data);  // Extraire les variables du tableau associatif
    include $view;   // Inclure le fichier de vue
}

// Appel de la fonction pour rendre la vue
renderView(__DIR__ . '/Views/Shared/Layout.php', ['user' => $lastUser]);
?>