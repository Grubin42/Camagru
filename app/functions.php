<?php

/**
 * Fonction pour rendre la vue.
 */
function renderView(string $layoutPath, array $data = []): void
{
    // Extraire les variables du tableau associatif
    extract($data);

    // Inclure le fichier de layout, qui inclura la vue spécifique
    include $layoutPath;
}

/**
 * render components 
 */
function renderComponent($component, $data = [])
{
    if (file_exists($component)) {
        extract($data); // Extracts array keys as variable names
        include $component;
    } else {
        print ($component); // to debug
        echo "The component specified does not exist.";
    }
}

