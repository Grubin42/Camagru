<?php

use Camagru\Core\Router;
use Camagru\Presentation\Controllers\ProfileController;
use Camagru\Presentation\Controllers\RegisterController;
use Camagru\Presentation\Controllers\LoginController;
use Camagru\Presentation\Controllers\PasswordResetController;
use Camagru\Presentation\Controllers\PostController;
use Camagru\Presentation\Controllers\LikeController;
use Camagru\Presentation\Controllers\CommentController;

$router = new Router();
// Route pour la page d'accueil

$router->addRoute('/', function() {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $postController = new PostController();
    $postController->showPosts($page);
});

$router->addRoute('/login', function() {
    $loginController = new LoginController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $loginController->authenticate();
    } else {
        $loginController->showLoginForm();
    }
});

$router->addRoute('/register', function() {
    $registerController = new RegisterController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $registerController->register();
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $postController->savePost();
        } else {
            $postController->showCreatePostForm();
        }
    } else {
        // Si l'utilisateur n'est pas connecté, redirection vers la page de connexion
        header('Location: /login');
        exit();
    }
});

$router->addRoute('/like-post', function() {
    $likeController = new LikeController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_SESSION['user'])) {
            // Gérer le like/unlike via le contrôleur
            $likeController->toggleLike(); 
        } else {
            // Si l'utilisateur n'est pas connecté, renvoyer une réponse JSON avec une erreur
            http_response_code(401); // Code HTTP 401 pour "Non autorisé"
            echo json_encode(['error' => 'Vous devez être connecté pour liker un post.']);
        }
    }
});

$router->addRoute('/add-comment', function() {
    $commentController = new CommentController();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $commentController->addComment();
    }
});

$router->addRoute('/profile', function() {
    if (isset($_SESSION['user'])) {
        $profileController = new ProfileController();
        $profileController->showProfile();
    } else {
        header('Location: /login');
        exit();
    }
});

$router->addRoute('/edit-profile', function() {
    if (isset($_SESSION['user'])) {
        $profileController = new ProfileController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileController->editProfile();
        } else {
            $profileController->showEditProfileForm();
        }
    } else {
        header('Location: /login');
        exit();
    }
});

return $router;