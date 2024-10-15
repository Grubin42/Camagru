<?php

namespace Camagru\Presentation\Controllers;

class ErrorController
{
    /**
     * Affiche une page d'erreur générique.
     *
     * @param string $errorMessage
     */
    public function showError($errorMessage = 'Une erreur est survenue.')
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Error/index.php',
            'error_message' => $errorMessage
        ]);
    }

    /**
     * Affiche une page 404 - Page non trouvée.
     */
    public function show404()
    {
        http_response_code(404);
        $this->showError('404 - Page non trouvée.');
    }

    /**
     * Affiche une page 500 - Erreur interne du serveur.
     */
    public function show500()
    {
        http_response_code(500);
        $this->showError('500 - Erreur interne du serveur.');
    }

    // Vous pouvez ajouter d'autres méthodes pour d'autres codes d'erreur si nécessaire.
}