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

