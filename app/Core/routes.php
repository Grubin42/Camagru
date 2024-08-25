<?php

use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\ProfileController;
use Presentation\Controllers\LoginController;
use Presentation\Controllers\PostController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->Index();
});

// // Route pour afficher le profile et le modifier
// $router->addRoute('/profile', function() {
//     $profileController = new ProfileController();
//     $profileController->Index();

// });

// Route pour ce connecter 
$router->addRoute('/login', function() {
    $loginController = new LoginController();
    $loginController->Index();

});

// // Route pour s'enregistrer
// $router->addRoute('/register', function() {
//     $loginController = new LoginController();
//     $loginController->Index();

// });

// Route pour ajouter un poste
$router->addRoute('/posts', function() {
    $postController = new PostController();
    $postController->showLastPosts();

});

return $router;