<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;
use Camagru\Core\Models\Comment;

class ProfileService {
    private $userModel;
    private $commentModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->commentModel = new Comment();
    }

    public function updateProfile($userId, $newUsername, $newEmail, $newPassword = null, $notif) {
        $errors = [];

        // Validation de l'email
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "L'adresse email est invalide.";
        }

        // Vérifier si le nom d'utilisateur est unique
        $existingUser = $this->userModel->findByUsername($newUsername);
        if ($existingUser && $existingUser['id'] !== $userId) {
            $errors[] = "Le nom d'utilisateur est déjà pris.";
        }

        // Vérification du mot de passe (si un nouveau mot de passe est fourni)
        if ($newPassword && strlen($newPassword) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères.";
        }

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        // Mettre à jour les informations dans la base de données
        $this->userModel->updateUserProfile($userId, $newUsername, $newEmail, $newPassword, $notif);
    
        // Mettre à jour les commentaires associés avec le nouveau nom d'utilisateur
        $this->commentModel->updateUsernameInComments($userId, $newUsername);

        return ['success' => true];
    }
}