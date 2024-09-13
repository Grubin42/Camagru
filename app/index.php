<?php

namespace Camagru;
// PRESENTATION
require_once __DIR__ . '/Presentation/Controllers/HomeController.php';
require_once __DIR__ . '/Presentation/Controllers/LoginController.php';
require_once __DIR__ . '/Presentation/Controllers/ProfileController.php';
require_once __DIR__ . '/Presentation/Controllers/RegisterController.php';
require_once __DIR__ . '/Presentation/Controllers/PostController.php';
require_once __DIR__ . '/Presentation/Controllers/LogoutController.php';

//MODEL
require_once __DIR__ . '/Core/Models/CommentModel.php';
require_once __DIR__ . '/Core/Models/LikeModel.php';
require_once __DIR__ . '/Core/Models/PostModel.php';
require_once __DIR__ . '/Core/Models/UserModel.php';

//SERVICE
require_once __DIR__ . '/Infrastructure/Services/ProfileService.php';
require_once __DIR__ . '/Infrastructure/Services/RegisterService.php';
require_once __DIR__ . '/Infrastructure/Services/PostService.php';
require_once __DIR__ . '/Infrastructure/Services/LoginService.php';
require_once __DIR__ . '/Infrastructure/Services/HomeService.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Router.php';
// require_once __DIR__ . '/Core/routes.php'; // Charger les routes
session_start();
// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);