<?php

use Camagru\Core\Router;
use Camagru\Core\models\User;

require_once __DIR__ . '/functions.php'; // Inclure les fonctions globales

$router = new Router();

// Route pour la page d'accueil
$router->addRoute('/', function() {
    renderView(__DIR__ . '/../Presenter/views/shared/Layout.php', [
        'view' => __DIR__ . '/../Presenter/views/home/index.php'
    ]);
});

// Route pour afficher le dernier utilisateur
$router->addRoute('/last-user', function() {
    //TODO: faire un controller
    $userModel = new User();
    $lastUser = $userModel->getLastUser();
    renderView(__DIR__ . '/../Presenter/views/shared/Layout.php', [
        'view' => __DIR__ . '/../Presenter/views/Users/index.php',
        'user' => $lastUser
    ]);
});

// Ajouter d'autres routes ici...

return $router;