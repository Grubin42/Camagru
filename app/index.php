<?php

namespace Camagru;

require_once __DIR__ . '/Core/Data/Connection.php';
require_once __DIR__ . '/Core/Models/User.php';
require_once __DIR__ . '/Core/Router.php';
require_once __DIR__ . '/Core/routes.php'; // Charger les routes

// Charger le routeur avec les routes définies
$router = require __DIR__ . '/Core/routes.php';

// Récupérer le chemin demandé
$requestedPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Gérer la requête via le routeur
$router->handleRequest($requestedPath);