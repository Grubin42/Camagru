<?php

namespace Camagru;

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Core/functions.php'; // Inclure les fonctions globales si nécessaire
require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Models/User.php';
require_once __DIR__ . '/Core/Models/Post.php';
require_once __DIR__ . '/Infrastructure/Services/PostService.php';
require_once __DIR__ . '/Presentation/Controllers/PostController.php';
require_once __DIR__ . '/Core/Router.php';

// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);