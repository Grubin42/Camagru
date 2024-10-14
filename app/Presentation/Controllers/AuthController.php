<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\TokenService;
use Camagru\Infrastructure\Services\ValidationService;
use Camagru\Infrastructure\Services\CsrfService;

class AuthController
{
    protected $tokenService;
    protected ValidationService $validationService;
    protected CsrfService $csrfService;

    public function __construct()
    {
        $this->tokenService = new TokenService();
        $this->validationService = new ValidationService();
        $this->csrfService = new CsrfService();
    }

    /**
     * Affiche le formulaire de demande de réinitialisation de mot de passe
     * et gère l'envoi du lien de réinitialisation.
     */
    public function requestPasswordReset()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Démarrer la session si ce n'est pas déjà fait
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $email = trim($_POST['email'] ?? '');

            if ($this->tokenService->sendResetLink($email)) {
                // Stocker le message de succès dans la session
                $_SESSION['success_message'] = "Un lien de réinitialisation a été envoyé à l'adresse email fournie.";
            } else {
                // Stocker le message d'erreur dans la session
                $_SESSION['error_message'] = "L'email n'existe pas dans notre base de données.";
            }

            // Rediriger vers la route GET pour afficher le formulaire avec les messages
            header('Location: /request-reset');
            exit();
        } else {
            // Générer le token CSRF
            $csrfToken = $this->csrfService->getToken();

            // Démarrer la session si ce n'est pas déjà fait
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Récupérer les messages depuis la session
            $success = $_SESSION['success_message'] ?? null;
            $error = $_SESSION['error_message'] ?? null;

            // Supprimer les messages de la session après les avoir récupérés
            unset($_SESSION['success_message'], $_SESSION['error_message']);

            // Afficher le formulaire de demande de réinitialisation
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Auth/request_reset.php',
                'success' => $success,
                'error' => $error,
                'csrf_token' => $csrfToken // Passer le token à la vue
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
                // Générer un nouveau token CSRF pour le formulaire de réinitialisation
                $csrfToken = $this->csrfService->getToken();
    
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                    'error' => "Veuillez corriger les erreurs ci-dessous.",
                    'errors' => $errors, // Passer les erreurs spécifiques
                    'token' => $token,
                    'csrf_token' => $csrfToken // Passer le token CSRF
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
                // Générer un nouveau token CSRF pour le formulaire de réinitialisation
                $csrfToken = $this->csrfService->getToken();
    
                $error = "Le token est invalide ou a expiré.";
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                    'error' => $error,
                    'token' => $token,
                    'csrf_token' => $csrfToken // Passer le token CSRF
                ]);
            }
        } else {
            $token = $_GET['token'] ?? '';
    
            // Générer le token CSRF
            $csrfToken = $this->csrfService->getToken();
    
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Auth/reset_password.php',
                'token' => $token,
                'csrf_token' => $csrfToken // Passer le token CSRF
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