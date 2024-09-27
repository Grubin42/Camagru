<?php

namespace Presentation\Controllers;

use Camagru\Core\Models\UserModel;

class ResetPasswordController
{
    public function resetPassword()
    {
        $errors = [];  // Initialiser le tableau des erreurs
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];
    
            // Vérification que le mot de passe n'est pas vide
            if (empty($newPassword)) {
                $errors[] = "Le mot de passe ne peut pas être vide.";
            }
    
            // Vérification que les mots de passe correspondent
            if ($newPassword !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
    
            // Vérification que le mot de passe contient au moins 5 caractères et une majuscule
            if (!empty($newPassword) && !preg_match('/^(?=.*[A-Z]).{5,}$/', $newPassword)) {
                $errors[] = 'Le mot de passe doit contenir au moins 5 caractères et au moins une majuscule.';
            }
    
            // Si aucune erreur, on vérifie le token et on réinitialise le mot de passe
            if (empty($errors)) {
                // Vérifier si le token est valide
                $userModel = new UserModel();
                $user = $userModel->verifyResetToken($token);
    
                if ($user) {
                    // Mettre à jour le mot de passe (hashé)
                    $userModel->resetPassword($user['id'], $newPassword);
    
                    // Message de succès et redirection
                    $_SESSION['success_message'] = "Mot de passe réinitialisé avec succès.";
                    header('Location: /login');
                    exit();
                } else {
                    // Si le token est invalide ou expiré
                    $errors[] = "Token invalide ou expiré.";
                }
            }
    
            // S'il y a des erreurs, les passer à la vue
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/ResetPassword/index.php',
                'errors' => $errors  // Passer les erreurs à la vue
            ]);
        } else {
            // Si la méthode est GET, afficher le formulaire avec le token
            $token = $_GET['token'] ?? '';
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/ResetPassword/index.php',
                'token' => $token
            ]);
            exit();
        }
    }
}