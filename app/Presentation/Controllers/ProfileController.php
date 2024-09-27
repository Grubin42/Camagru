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

    // Afficher le formulaire d'édition du profil
    public function showEditProfileForm() {
        $user = $_SESSION['user'];  // Récupérer les données de l'utilisateur depuis la session
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Profile/edit.php',
            'user' => $user
        ]);
    }

    // Mettre à jour le profil de l'utilisateur
    public function editProfile() {
        $userId = $_SESSION['user']['id'];
        $newUsername = $_POST['username'];
        $newEmail = $_POST['email'];
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
        } else {
            // Renvoyer à la vue avec des erreurs
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Profile/edit.php',
                'user' => $_SESSION['user'],
                'errors' => $result['errors']
            ]);
        }
    }
}