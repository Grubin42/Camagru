<?php

namespace Camagru;
// PRESENTATION
require_once __DIR__ . '/Presentation/Controllers/HomeController.php';
require_once __DIR__ . '/Presentation/Controllers/LoginController.php';
require_once __DIR__ . '/Presentation/Controllers/ProfileController.php';

//MODEL
require_once __DIR__ . '/Core/Models/ProfileModel.php';

//SERVICE
require_once __DIR__ . '/Infrastructure/Services/ProfileService.php';

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Router.php';

// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);