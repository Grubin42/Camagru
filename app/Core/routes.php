<?php

use Camagru\Core\Router;
use Presentation\Controllers\HomeController;
use Presentation\Controllers\ProfileController;
use Presentation\Controllers\LoginController;
use Presentation\Controllers\RegisterController;
use Presentation\Controllers\PostController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $homeController = new HomeController();
    $homeController->Index();
});

// Route pour afficher le profile et le modifier
$router->addRoute('/profile', function() {
    $profileController = new ProfileController();
    $profileController->Index();

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

// Route pour s'enregistrer
$router->addRoute('/register', function() {
    $registerController = new RegisterController();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $email = $_POST["email"];
        $registerController->Register($username, $password, $email);
    }
    $registerController->Index();

});
// Route pour ajouter un poste
$router->addRoute('/post', function() {
    $postController = new PostController();
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Vérifier que le fichier est bien envoyé
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $imageContent = file_get_contents($fileTmpPath);
            $postController->ImageRegister($imageContent);
            echo "L'image a été téléchargée et sauvegardée avec succès.";
        } else {
            echo "Une erreur s'est produite lors du téléchargement de l'image.";
        }
    }
    $postController->Index();
    
});

return $router;