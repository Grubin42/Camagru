<?php

use Camagru\Core\Router;
use Camagru\Core\Middleware\CsrfMiddleware;
use Camagru\Presentation\Controllers\ProfileController;
use Camagru\Presentation\Controllers\RegisterController;
use Camagru\Presentation\Controllers\LoginController;
use Camagru\Presentation\Controllers\AuthController;
use Camagru\Presentation\Controllers\PostController;
use Camagru\Presentation\Controllers\LikeController;
use Camagru\Presentation\Controllers\CommentController;
use Camagru\Presentation\Controllers\ErrorController;


$router = new Router();

// Ajouter le middleware CSRF
$router->addMiddleware(function() {
    $csrfMiddleware = new CsrfMiddleware();
    $csrfMiddleware->handle();
});

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

$router->addRoute('/register/success', function() {
    $registerController = new RegisterController();
    $registerController->showSuccess();
});

$router->addRoute('/verify-email', function() {
    $tokenController = new AuthController();

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $tokenController->verifyEmail();
    }
});

$router->addRoute('/logout', function() {
    $loginController = new LoginController();
    $loginController->logout();
});


$router->addRoute('/request-reset', function() {
    $TokenController = new AuthController();
    $TokenController->requestPasswordReset();
});

$router->addRoute('/reset-password', function() {
    $TokenController = new AuthController();
    $TokenController->resetPassword();
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

// Ajouter les routes d'erreur
$router->addRoute('/error', function() {
    $errorController = new ErrorController();
    $errorController->showError($_SESSION['error_message'] ?? 'Une erreur est survenue.');
});

$router->addRoute('/error/403', function() {
    $errorController = new ErrorController();
    $errorController->showError('403 - Accès interdit.');
});

$router->addRoute('/error/401', function() {
    $errorController = new ErrorController();
    $errorController->showError('401 - Non autorisé.');
});

$router->addRoute('/error/404', function() {
    $errorController = new ErrorController();
    $errorController->show404();
});

$router->addRoute('/error/500', function() {
    $errorController = new ErrorController();
    $errorController->show500();
});
return $router;