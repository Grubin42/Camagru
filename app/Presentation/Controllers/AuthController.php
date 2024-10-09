<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\TokenService;
use Camagru\Infrastructure\Services\ValidationService;

class AuthController
{
    protected $tokenService;
    protected ValidationService $validationService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
        $this->validationService = new ValidationService();
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
                $success = "Un lien de réinitialisation a été envoyé à l'adresse email fournie.";
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/request_reset.php',
                    'success' => $success
                ]);
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
     * Réinitialise le mot de passe.
     */
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $newPassword = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
    
            // Réinitialiser les erreurs de validation
            $this->validationService->resetErrors();
    
            // Vérifier si les mots de passe correspondent
            if ($newPassword !== $confirmPassword) {
                $this->validationService->addError('confirm_password', "Les mots de passe ne correspondent pas.");
            }
    
            // Valider la complexité du mot de passe
            if (!$this->validationService->validatePassword($newPassword)) {
                // Les erreurs sont déjà ajoutées par validatePassword
            }
    
            // Récupérer les erreurs
            $errors = $this->validationService->getErrors();
            if (!empty($errors)) {
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                    'error' => "Veuillez corriger les erreurs ci-dessous.",
                    'errors' => $errors, // Passer les erreurs spécifiques
                    'token' => $token
                ]);
                return;
            }
    
            // Si toutes les validations sont passées, procéder à la réinitialisation
            if ($this->tokenService->resetPassword($token, $newPassword)) {
                // Redirection après succès
                header('Location: /login?reset=success');
                exit();
            } else {
                // Message d'erreur si le token est invalide ou expiré
                $error = "Le token est invalide ou a expiré.";
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                    'error' => $error,
                    'token' => $token
                ]);
            }
        } else {
            $token = $_GET['token'] ?? '';
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                'token' => $token
            ]);
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