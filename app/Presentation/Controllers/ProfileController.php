<?php

namespace Camagru\Presentation\Controllers;

use Camagru\Infrastructure\Services\ProfileService;

class ProfileController {
    private $profileService;

    public function __construct() {
        $this->profileService = new ProfileService();
    }

    // Afficher le profil de l'utilisateur
    public function showProfile() {
        $user = $_SESSION['user'];  // Récupérer les données de l'utilisateur depuis la session
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Profile/index.php',
            'user' => $user
        ]);
    }

    /**
     * Affiche le formulaire de modification de profil.
     */
    public function showEditProfileForm()
    {
        // Récupérer les erreurs et les données de formulaire de la session s'il y en a
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $errorMessage = $_SESSION['error_message'] ?? null;
        $errors = $_SESSION['errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? $_SESSION['user'] ?? [];
        unset($_SESSION['error_message'], $_SESSION['errors'], $_SESSION['form_data']);

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Profile/edit.php',
            'error' => $errorMessage,
            'errors' => $errors,
            'user' => $formData
        ]);
    }

    /**
     * Gère la soumission du formulaire de modification de profil.
     */
    public function editProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $userId = $_SESSION['user']['id'];
            $newUsername = trim($_POST['username'] ?? '');
            $newEmail = trim($_POST['email'] ?? '');
            $newPassword = $_POST['password'] ?? null;
            $notif = isset($_POST['notif']) ? true : false; // Si la case à cocher est cochée

            // Validation et mise à jour via ProfileService
            $result = $this->profileService->updateProfile($userId, $newUsername, $newEmail, $newPassword, $notif);

            if ($result['success']) {
                // Mettre à jour les informations dans la session
                $_SESSION['user']['username'] = $newUsername;
                $_SESSION['user']['email'] = $newEmail;
                $_SESSION['user']['notif'] = $notif;

                header('Location: /profile');
                exit();
            } else {
                // Renvoyer à la vue avec des erreurs
                $_SESSION['error_message'] = "Veuillez corriger les erreurs ci-dessous.";
                $_SESSION['errors'] = $result['errors'];
                $_SESSION['form_data'] = [
                    'username' => $newUsername,
                    'email' => $newEmail,
                    'notif' => $notif
                ];

                // Rediriger vers la page de modification de profil
                header('Location: /edit-profile');
                exit();
            }
        } else {
            // Si la requête n'est pas POST, afficher le formulaire de modification de profil
            $this->showEditProfileForm();
        }
    }
}