<?php

use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\ProfileController;
use Presentation\Controllers\LoginController;
use Presentation\Controllers\RegisterController;
use Presentation\Controllers\PostController;
use Presentation\Controllers\LogoutController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->Index();
});

// Route pour afficher le profile et le modifier
$router->addRoute('/profile', function() {
    if(isset($_SESSION['user'])) 
    {
        $profileController = new ProfileController();
        $profileController->Index();
    }
    header("Location: /login");

});

// Route pour ce connecter 
$router->addRoute('/login', function() {
    $loginController = new LoginController();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $loginController->Login($username, $password);
    }
    $loginController->Index();
});
// Route pour ce connecter 
$router->addRoute('/logout', function() {
    $loginController = new LogoutController();
    $loginController->Index();
});

// Route pour s'enregistrer
$router->addRoute('/register', function() {
    $registerController = new RegisterController();
    $error = null;

    // Vérifier si une requête POST a été envoyée
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $password1 = $_POST["password1"];
        $email = $_POST["email"];
        
        // Appeler la méthode Register du contrôleur
        $result = $registerController->Register($username, $password, $password1, $email);

        // Vérifier si la méthode Register a retourné une erreur
        if (isset($result['error'])) {
            $error = $result['error'];
        }
    }

    // Appeler la méthode Index du contrôleur et passer l'erreur si elle existe
    $registerController->Index($error);
});

// Route pour ajouter un poste
$router->addRoute('/post', function() {
    if(isset($_SESSION['user'])) 
    {
        $postController = new PostController();
        $postController->Index();
    }
    header("Location: /login");
});

$router->addRoute('/post/save', function() {
    if(isset($_SESSION['user'])) 
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $postController = new PostController();
            $postController->SavePost();
        }
    }
    header("Location: /login");
});

return $router;