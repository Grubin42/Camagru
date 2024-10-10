<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\LoginService;

class LoginController {
    private $Login;

    public function __construct() {
        $this->Login = new LoginService();
    }

    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Login/index.php'
        ]);
    }
    
    public function Login() {
        $username = $_POST["username"];
        $password = $_POST["password"];
        $errors = [];  // Initialiser le tableau des erreurs

        if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
            $errors[] = 'Erreur : jeton CSRF invalide.';
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Login/index.php',
                'errors' => $errors  // Passer les erreurs à la vue
            ]);
            exit();
        }
        // Récupérer l'utilisateur avec le nom d'utilisateur
        $user = $this->Login->Login($username, $password);
        
        // Vérifie si l'utilisateur existe et que le mot de passe est correct
        if ($user && password_verify($password, $user['password'])) {
            // Vérifie si l'utilisateur a validé son compte (is_verified doit être TRUE)
            if ($user['is_verified']) {
                // Si l'utilisateur est vérifié, on le connecte
                $_SESSION['user'] = $user;
                unset($_SESSION['csrf_token']);
                header('Location: /');
                exit();
            } else {
                // Si l'utilisateur n'a pas encore validé son compte
                $errors[] = 'Votre compte n\'est pas encore validé. Veuillez vérifier vos emails.';
            }
        } else {
            // Si les informations de connexion sont incorrectes
            $errors[] = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    
        // S'il y a des erreurs, les renvoyer dans la vue
        if (!empty($errors)) {
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Login/index.php',
                'errors' => $errors  // Passer les erreurs à la vue
            ]);
            exit();
        }
    }
}