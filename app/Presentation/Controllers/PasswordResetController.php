<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\PasswordResetService;

class PasswordResetController
{
    protected $passwordResetService;

    public function __construct()
    {
        $this->passwordResetService = new PasswordResetService();
    }

    /**
     * Affiche le formulaire de demande de réinitialisation de mot de passe
     * et gère l'envoi du lien de réinitialisation.
     */
    public function requestPasswordReset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            if ($this->passwordResetService->sendResetLink($email)) {
                // Rediriger vers une page de confirmation
                header('Location: /request-reset/sent');
                exit();
            } else {
                // Afficher un message d'erreur
                $error = "L'email n'existe pas dans notre base de données.";
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/request_reset.php',
                    'error' => $error
                ]);
            }
        } else {
            // Afficher le formulaire de demande de réinitialisation
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Auth/request_reset.php'
            ]);
        }
    }

    /**
     * Affiche le formulaire de réinitialisation de mot de passe
     * et gère la réinitialisation.
     */
    public function resetPassword()
    {
        // Implémentation pour la réinitialisation du mot de passe
    }
}