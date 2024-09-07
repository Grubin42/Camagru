<?php

use Camagru\Core\Router;
use Camagru\Presentation\Controllers\HomeController;
use Camagru\Presentation\Controllers\RegisterController;
use Camagru\Presentation\Controllers\LoginController;
use Camagru\Presentation\Controllers\PasswordResetController;
use Camagru\Presentation\Controllers\PostController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->showHomePage();
});

$router->addRoute('/login', function() {
    $loginController = new LoginController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $loginController->authenticate($username, $password);
    } else {
        $loginController->showLoginForm();
    }
});

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

$router->addRoute('/posts', function() {
    // Vérification si l'utilisateur est connecté
    if (isset($_SESSION['user'])) {
        $postController = new PostController();
        $postController->showCreatePostForm();
    } else {
        // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
        header('Location: /login');
        exit();
    }
});

$router->addRoute('/posts/save', function() {
    $postController = new PostController();
    $postController->savePost();
});

return $router;