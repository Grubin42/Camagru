<?php

namespace Camagru;
//MIDDLEWARE
require_once __DIR__ . '/Core/Middleware/CsrfMiddleware.php';

//MODEL
require_once __DIR__ . '/Core/Models/Post.php';
require_once __DIR__ . '/Core/Models/User.php';
require_once __DIR__ . '/Core/Models/Comment.php';
require_once __DIR__ . '/Core/Models/Like.php';
require_once __DIR__ . '/Core/Models/Token.php';

// Controller
require_once __DIR__ . '/Presentation/Controllers/HomeController.php';
require_once __DIR__ . '/Presentation/Controllers/LoginController.php';
require_once __DIR__ . '/Presentation/Controllers/ProfileController.php';
require_once __DIR__ . '/Presentation/Controllers/PostController.php';
require_once __DIR__ . '/Presentation/Controllers/RegisterController.php';
require_once __DIR__ . '/Presentation/Controllers/AuthController.php';
require_once __DIR__ . '/Presentation/Controllers/LikeController.php';
require_once __DIR__ . '/Presentation/Controllers/CommentController.php';

//SERVICE
require_once __DIR__ . '/Infrastructure/Services/HomeService.php';
require_once __DIR__ . '/Infrastructure/Services/LoginService.php';
require_once __DIR__ . '/Infrastructure/Services/PostService.php';
require_once __DIR__ . '/Infrastructure/Services/ProfileService.php';
require_once __DIR__ . '/Infrastructure/Services/RegisterService.php';
require_once __DIR__ . '/Infrastructure/Services/TokenService.php';
require_once __DIR__ . '/Infrastructure/Services/MailService.php';
require_once __DIR__ . '/Infrastructure/Services/LikeService.php';
require_once __DIR__ . '/Infrastructure/Services/CommentService.php';
require_once __DIR__ . '/Infrastructure/Services/ValidationService.php';
require_once __DIR__ . '/Infrastructure/Services/CSRFService.php';

//CONFIG
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Router.php';

session_start();
// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);