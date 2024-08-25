<?php

use Camagru\Controller\UserController;
use Camagru\Core\Router;
use Camagru\Core\models\User;
use Camagru\Controller\HomeController;

require_once __DIR__ . '/functions.php'; // Inclure les fonctions globales

$router = new Router();

// Route pour la page d'accueil
$router->addRoute('/', function() {
    $controller = new HomeController();
    $controller->index();
});

$router->addRoute('/settings', function() {
    $controller = new UserController();
    $controller->index();
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

$router->addRoute('/feed', function() {
    // $postModel = new Post();
    // $posts = $postModel->getAllPostsWithComments();

    renderView(__DIR__ . '/../Presenter/views/shared/Layout.php', [
        'view' => __DIR__ . '/../Presenter/views/feed/index.php',
        // 'posts' => $posts
    ]);
});

// Ajouter d'autres routes ici...

return $router;