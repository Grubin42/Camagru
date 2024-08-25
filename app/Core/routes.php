<?php

use Camagru\Core\Router;
use Camagru\Presentation\Controllers\HomeController;

$router = new Router();
// Route pour la page d'accueil


$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->showHomePage();
});

// // Route pour afficher le dernier utilisateur
// $router->addRoute('/profile', function() {
//     $profileController = new ProfileController();
//     $profileController->Index();

// });

// // Route pour afficher le dernier utilisateur
// $router->addRoute('/login', function() {
//     $loginController = new LoginController();
//     $loginController->Index();

// });
// Ajouter d'autres routes ici...

return $router;