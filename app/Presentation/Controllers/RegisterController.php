<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class RegisterController {
    private $Register;

    public function __construct() {
        $this->Register = new RegisterService();
    }


    public function Index($error = null) {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php',
            'error' => $error  // Passer l'erreur à la vue
        ]);
    }
    public function Register(): void {
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';
        $errors = [];
    
        // Vérifier si les mots de passe correspondent
        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            } elseif (!preg_match('/^(?=.*[A-Z]).{5,}$/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins 5 caractères et au moins une majuscule.';
            }
        }
    
        // Vérifier si le nom d'utilisateur est déjà utilisé
        if ($this->Register->isUsernameTaken($username)) {
            $errors[] = 'Le nom d\'utilisateur est déjà pris.';
        }

        if (empty($email)) {
            $errors[] = "L'email est obligatoire.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Veuillez entrer un email valide.";
        }
        
        // Si des erreurs sont présentes, les renvoyer
        if (!empty($errors)) {
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Register/index.php',
                'errors' => $errors
            ]);
            exit();
        }
        else{
            
            // Si tout va bien, appeler la méthode d'enregistrement du modèle
            $this->Register->Register($username, $password, $email); // Noter que password1 n'est pas nécessaire ici
        
            // Redirection après enregistrement réussi
            header('Location: /login');
            exit;
        }
    }
}
