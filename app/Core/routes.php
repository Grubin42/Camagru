<?php

use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\RegisterController;
use Presentation\Controllers\LoginController;
use Presentation\Controllers\PostController;
use Presentation\Controllers\CommentController;
use Presentation\Controllers\PasswordResetController;

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

$router->addRoute('/register', function() {
    $registerController = new RegisterController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $registerController->register($username, $email, $password);
    } else {
        $registerController->showRegisterForm();
    }
});

// Route pour ce connecter 
$router->addRoute('/login', function() {

    $loginController = new LoginController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $loginController->authenticate($username, $password);
    } else {
        $loginController->Index();
    }

    // $loginController = new LoginController();
    // $loginController->Index();
});

$router->addRoute('/logout', function() {
    $loginController = new LoginController();
    $loginController->logout();
});

$router->addRoute('/request-reset', function() {
    $passwordResetController = new PasswordResetController();
    $passwordResetController->requestPasswordReset();
});

$router->addRoute('/reset-password', function() {
    $passwordResetController = new PasswordResetController();
    $passwordResetController->resetPassword();
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