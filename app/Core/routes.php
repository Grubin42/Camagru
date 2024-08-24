<?php

use Camagru\Core\Router;
use Camagru\Core\Models\UserModel;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\UserController;

require_once __DIR__ . '/functions.php'; // Inclure les fonctions globales

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->Index();
});

// Route pour afficher le dernier utilisateur
$router->addRoute('/last-user', function() {
    $homeController = new UserController();
    $homeController->Index();

});

// Ajouter d'autres routes ici...

return $router;