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
    public function Register(string $username, $password, $password1, $email) {
        // Vérifier si les mots de passe correspondent
        if ($password !== $password1) {
            return [
                'error' => 'Les mots de passe ne correspondent pas.'
            ];
        }

        // Si tout va bien, appeler la méthode d'enregistrement du modèle
        $this->Register->Register($username, $password, $email); // Noter que password1 n'est pas nécessaire ici

        // Redirection après enregistrement réussi
        header('Location: /login');
        exit;
    }
}
