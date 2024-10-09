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

    public function showRegisterForm()
    {
        // Récupérer les erreurs de la session s'il y en a
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $errorMessage = $_SESSION['error_message'] ?? null;
        unset($_SESSION['error_message']);

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php',
            'error' => $errorMessage
        ]);
    }

    public function showSuccess()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/success.php'
        ]);
    }

    public function register()
    {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        if ($this->registerService->register($username, $email, $password)) {
            // Rediriger vers une page indiquant que l'email de vérification a été envoyé
            header('Location: /register/success');
            exit();
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
    
            // Récupérer les erreurs et les valeurs validées du service
            $_SESSION['error_message'] = implode('<br>', $this->registerService->getErrors());
            $_SESSION['form_data'] = [
                'username' => $this->registerService->isValid('username') ? $username : '',
                'email' => $this->registerService->isValid('email') ? $email : '',
                'password' => '' // Ne jamais pré-remplir les mots de passe après une tentative de soumission pour des raisons de sécurité
            ];
            
            // Rediriger vers la page d'inscription
            header('Location: /register');
            exit();
        }
    }
}