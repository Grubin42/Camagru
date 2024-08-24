<?php

use Camagru\Core\Router;
use Camagru\Core\Models\User;
use Camagru\Presentation\Controllers\PostController;

$router = new Router();

// Route pour la page d'accueil avec les derniers posts
$router->addRoute('/', function() {
    $postController = new PostController();
    $postController->showLastPosts();
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