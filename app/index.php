<?php
namespace Camagru;

// PRESENTATION
require_once __DIR__ . '/Presentation/Controllers/HomeController.php';
require_once __DIR__ . '/Presentation/Controllers/LoginController.php';
require_once __DIR__ . '/Presentation/Controllers/ProfileController.php';
require_once __DIR__ . '/Presentation/Controllers/RegisterController.php';
require_once __DIR__ . '/Presentation/Controllers/PostController.php';
require_once __DIR__ . '/Presentation/Controllers/LogoutController.php';
require_once __DIR__ . '/Presentation/Controllers/ErrorController.php';
require_once __DIR__ . '/Presentation/Controllers/ForgotPasswordController.php';
require_once __DIR__ . '/Presentation/Controllers/ResetPasswordController.php';
require_once __DIR__ . '/Presentation/Controllers/VerificationController.php';

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
require_once __DIR__ . '/Infrastructure/Services/EmailService.php';
require_once __DIR__ . '/Infrastructure/Services/ResetPasswordService.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Router.php';
// require_once __DIR__ . '/Core/routes.php'; // Charger les routes
// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);