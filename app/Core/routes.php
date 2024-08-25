<?php

use Camagru\Controller\UserController;
use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\ProfileController;
use Presentation\Controllers\LoginController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->Index();
});

// Route pour afficher le dernier utilisateur
$router->addRoute('/profile', function() {
    $profileController = new ProfileController();
    $profileController->Index();

});

// Route pour afficher le dernier utilisateur
$router->addRoute('/login', function() {
    $loginController = new LoginController();
    $loginController->Index();

});
// Ajouter d'autres routes ici...

return $router;