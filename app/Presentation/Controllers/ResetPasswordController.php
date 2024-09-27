<?php

namespace Presentation\Controllers;

use Camagru\Core\Models\UserModel;

class ResetPasswordController
{
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if ($newPassword === $confirmPassword) {
                // Vérifier si le token est valide
                $userModel = new UserModel();
                $user = $userModel->verifyResetToken($token);

                if ($user) {
                    // Mettre à jour le mot de passe (hashé)
                    $userModel->resetPassword($user['id'], $newPassword);

                    echo "Mot de passe réinitialisé avec succès.";
                } else {
                    echo "Token invalide ou expiré.";
                }
            } else {
                echo "Les mots de passe ne correspondent pas.";
            }
        }
        else{

            $token = $_GET['token'] ?? '';
            renderView(__DIR__ . '/../Views/ResetPassword/index.php');
            exit();
        }
    }
}