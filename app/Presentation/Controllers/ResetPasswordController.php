<?php

namespace Presentation\Controllers;

use Camagru\Core\Models\UserModel;
use Camagru\Infrastructure\Services\ResetPasswordService;


class ResetPasswordController
{
    private $ResetPasswordService;

    public function __construct() {
        $this->ResetPasswordService = new ResetPasswordService();
    }
    public function resetPassword()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $newPassword = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                $errors[] = 'Erreur : jeton CSRF invalide.';
                renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                    'view' => __DIR__ . '/../Views/ResetPassword/index.php',
                    'errors' => $errors  // Passer les erreurs à la vue
                ]);
                exit();
            }

            $errors = $this->ResetPasswordService->verificationResetPassword($newPassword, $confirmPassword);
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
                    unset($_SESSION['csrf_token']);
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