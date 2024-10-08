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
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php'
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
            // Utiliser les sessions pour stocker le message d'erreur
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['error_message'] = 'L\'utilisateur existe déjà ou une erreur est survenue.';
            header('Location: /register');
            exit();
        }
    }
}