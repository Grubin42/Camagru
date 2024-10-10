<?php

/**
 * Fonction pour rendre la vue.
 */
function renderView(string $layoutPath, array $data = []): void {
    // Extraire les variables du tableau associatif
    extract($data);

    // Inclure le fichier de layout, qui inclura la vue spécifique
    include $layoutPath;
}

function GenerateCsrfToken(): string {
    // Vérifie si un jeton CSRF existe déjà dans la session
    if (empty($_SESSION['csrf_token'])) {
        // Si aucun jeton n'existe, en génère un nouveau
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    // Retourne le jeton CSRF de la session
    return $_SESSION['csrf_token'];
}