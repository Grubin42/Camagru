<?php

use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\LoginController;
use Presentation\Controllers\PostController;
use Presentation\Controllers\CommentController;

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


$router->addRoute('/home', function() {
    $homecontroller = new HomeController();
    $homecontroller->Index();
});

// Route pour ajouter un poste
$router->addRoute('/posts', function() {
    $postController = new PostController();
    $postController->createPost();
});

$router->addRoute('/supperpose-images', function() {
    $postController = new PostController();
    $postController->handleFormSubmit();
});

$router->addRoute('/publish', function() {
        $postController = new PostController();
        $postController->publishPost();
});

$router->addRoute('/publish/confirmation', function() {
    $postController = new PostController();
    $postController->confirmationPublishPost();
});

$router->addRoute('/comment', function() {
    $commentController = new CommentController();
    $commentController->saveComment();
});

return $router;