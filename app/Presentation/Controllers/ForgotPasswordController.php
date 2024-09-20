<?php

namespace Presentation\Controllers;

use Camagru\Core\Models\UserModel;
use Camagru\Infrastructure\Services\EmailService;

class ForgotPasswordController {
    public function showForgotPasswordForm()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/ForgotPassword/index.php',
        ]);
    }
    public function sendResetLink()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            
            // Vérifier si l'email existe dans la base de données
            $userModel = new UserModel();
            // aller dans le service
            $user = $userModel->findUserByEmail($email);
            
            if ($user) {
                // Générer un token unique et une URL de réinitialisation
                $token = bin2hex(random_bytes(50));
                $resetUrl = "http://localhost/reset-password?token=$token";
                
                // Sauvegarder le token dans la base de données pour cet utilisateur
                $userModel->storeResetToken($email, $token);

                // Envoyer le lien de réinitialisation par email
                $emailService = new EmailService();
                $emailService->sendPasswordResetEmail($email, $resetUrl);
                
                echo "Un email de réinitialisation a été envoyé à votre adresse.";
            } else {
                echo "Cet email n'existe pas.";
            }
        }
    }
}