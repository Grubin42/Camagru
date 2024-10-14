<?php

namespace Camagru\Core\Middleware;

use Camagru\Infrastructure\Services\CsrfService;

class CsrfMiddleware
{
    protected CsrfService $csrfService;

    public function __construct()
    {
        $this->csrfService = new CsrfService();
    }

    /**
     * Gère la requête entrante et valide le token CSRF si nécessaire.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer le token CSRF depuis les données POST ou les headers
            $token = $_POST['_csrf_token'] ?? (isset($_SERVER['HTTP_X_CSRF_TOKEN']) ? $_SERVER['HTTP_X_CSRF_TOKEN'] : '');

            if (!$this->csrfService->validateToken($token)) {
                // Gérer le token CSRF invalide, par exemple en définissant une erreur et en redirigeant
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Vérifier si la requête est AJAX
                if ($this->isAjaxRequest()) {
                    http_response_code(403); // Forbidden
                    echo json_encode(['error' => 'Token CSRF invalide.']);
                } else {
                    $_SESSION['error_message'] = 'Token CSRF invalide.';
                    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/'));
                }
                exit();
            } else {
                // Supprimer le token après une validation réussie (pour tokens à usage unique)
                //$this->csrfService->removeToken();
            }
        }
    }

    /**
     * Détermine si la requête est une requête AJAX.
     *
     * @return bool
     */
    protected function isAjaxRequest(): bool
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}