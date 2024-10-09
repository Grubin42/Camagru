<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Core\Models\Token;
use Camagru\Infrastructure\Services\TokenService;

class AuthController
{
    protected $tokenService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
    }

    /**
     * Affiche le formulaire de demande de réinitialisation de mot de passe
     * et gère l'envoi du lien de réinitialisation.
     */
    public function requestPasswordReset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';

            if ($this->tokenService->sendResetLink($email)) {
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['password'] ?? '';

            if ($this->tokenService->resetPassword($token, $newPassword)) {
                // Redirection après succès
                header('Location: /login?reset=success');
                exit();
            } else {
                // Message d'erreur si le token est invalide ou expiré
                $error = "Le token est invalide ou a expiré.";
                renderView(__DIR__ . '/../Views/Auth/reset_password.php', ['error' => $error]);
            }
        } else {
            $token = $_GET['token'] ?? '';
            renderView(__DIR__ . '/../Views/Auth/reset_password.php', ['token' => $token]);
        }
    }

    /**
     * Vérifie le token de vérification d'email.
     */
    public function verifyEmail()
    {
        $token = $_GET['token'] ?? '';

        if ($this->tokenService->verifyEmail($token)) {
            // Rediriger vers la page d'accueil avec un message de succès
            header('Location: /?verified=success');
            exit();
        } else {
            // Rediriger vers la page d'accueil avec un message d'erreur
            header('Location: /?verified=error');
            exit();
        }
    }
}