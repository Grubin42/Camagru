<?php
// Configuration de la base de données
define('DB_HOST', getenv('POSTGRES_HOST'));
define('DB_NAME', getenv('POSTGRES_DB'));
define('DB_USER', getenv('POSTGRES_USER'));
define('DB_PASS', getenv('POSTGRES_PASSWORD'));