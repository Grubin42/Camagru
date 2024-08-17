<?php

use Camagru\Core\Router;
use Camagru\Core\Models\User;

require_once __DIR__ . '/functions.php'; // Inclure les fonctions globales

$router = new Router();

// Route pour la page d'accueil
$router->addRoute('/', function() {
    renderView(__DIR__ . '/../Presentation/Views/Shared/Layout.php', [
        'view' => __DIR__ . '/../Presentation/Views/Home/index.php'
    ]);
});

// Route pour afficher le dernier utilisateur
$router->addRoute('/last-user', function() {
    $userModel = new User();
    $lastUser = $userModel->getLastUser();
    renderView(__DIR__ . '/../Presentation/Views/Shared/Layout.php', [
        'view' => __DIR__ . '/../Presentation/Views/Users/index.php',
        'user' => $lastUser
    ]);
});

// Ajouter d'autres routes ici...

return $router;