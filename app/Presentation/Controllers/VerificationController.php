<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class VerificationController {
    private $RegisterService;

    public function __construct() {
        $this->RegisterService = new RegisterService();
    }

    public function verify(){
        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $user = $this->RegisterService->findUserByToken($token);

            if ($user) {
                // Marquer l'utilisateur comme vérifié
                $this->RegisterService->verifyUser($user['id']);
                $_SESSION['success_message'] = 'Votre compte a été vérifié avec succès.';
                header('Location: /login');
                exit();
            } else {
                $_SESSION['error_message'] = 'Le lien de validation est invalide ou expiré.';
                header('Location: /login');
                exit();
            }
        }
    }
}