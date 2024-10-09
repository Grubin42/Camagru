<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class RegisterController
{
    protected RegisterService $registerService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
    }

    /**
     * Affiche le formulaire d'inscription.
     */
    public function showRegisterForm()
    {
        // Récupérer les erreurs et les données de formulaire de la session s'il y en a
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $errorMessage = $_SESSION['error_message'] ?? null;
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['error_message'], $_SESSION['form_data']);

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php',
            'error' => $errorMessage,
            'form_data' => $formData
        ]);
    }

    /**
     * Gère la soumission du formulaire d'inscription.
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if ($this->registerService->register($username, $email, $password)) {
                // Rediriger vers une page indiquant que l'email de vérification a été envoyé
                header('Location: /register/success');
                exit();
            } else {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                // Récupérer les erreurs et les valeurs validées du service
                $errors = $this->registerService->getErrors();
                $_SESSION['error_message'] = "Veuillez corriger les erreurs ci-dessous.";
                $_SESSION['form_data'] = [
                    'username' => $this->registerService->isValid('username') ? $username : '',
                    'email' => $this->registerService->isValid('email') ? $email : '',
                    'password' => '' // Ne jamais pré-remplir les mots de passe après une tentative de soumission pour des raisons de sécurité
                ];

                // Rediriger vers la page d'inscription
                header('Location: /register');
                exit();
            }
        } else {
            // Si la requête n'est pas POST, afficher le formulaire d'inscription
            $this->showRegisterForm();
        }
    }

    /**
     * Affiche la page de succès après inscription.
     */
    public function showSuccess()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/success.php'
        ]);
    }
}