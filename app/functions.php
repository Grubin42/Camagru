<?php

/**
 * Fonction pour rendre la vue.
 */
function renderView(string $viewPath, array $data = []): void {

     $viewBasePath = __DIR__ . '/../Presenter/views/';
    // Extraire les variables du tableau associatif
    extract($data);

    // Inclure le fichier de layout, qui inclura la vue spécifique
    include $viewBasePath .  $viewPath;
}

