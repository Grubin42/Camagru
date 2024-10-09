<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;
use Camagru\Core\Models\Comment;

class ProfileService {
    private User $userModel;
    private Comment $commentModel;
    private ValidationService $validationService;
    
    public function __construct() {
        $this->userModel = new User();
        $this->commentModel = new Comment();
        $this->validationService = new ValidationService();
    }

    /**
     * Tente de mettre à jour le profil de l'utilisateur.
     *
     * @param int $userId
     * @param string $newUsername
     * @param string $newEmail
     * @param string|null $newPassword
     * @param bool $notif
     * @return array
     */
    public function updateProfile(int $userId, string $newUsername, string $newEmail, ?string $newPassword = null, bool $notif): array {
        $this->validationService->resetErrors();

        // Valider le nom d'utilisateur
        $isUsernameValid = $this->validationService->validateUsername($newUsername);

        // Valider l'email
        $isEmailValid = $this->validationService->validateEmail($newEmail);

        // Valider le mot de passe si fourni
        $isPasswordValid = true;
        if ($newPassword !== null && $newPassword !== '') {
            $isPasswordValid = $this->validationService->validatePassword($newPassword);
        }

        // Vérifier si le nom d'utilisateur est unique
        $existingUser = $this->userModel->findByUsername($newUsername);
        if ($existingUser && $existingUser['id'] !== $userId) {
            $this->validationService->addError('username', "Le nom d'utilisateur est déjà pris.");
            $isUsernameValid = false;
        }

        // Vérifier si l'email est unique
        $existingEmailUser = $this->userModel->findByEmail($newEmail);
        if ($existingEmailUser && $existingEmailUser['id'] !== $userId) {
            $this->validationService->addError('email', "L'email est déjà utilisé.");
            $isEmailValid = false;
        }

        // Si des erreurs sont présentes, renvoyer false
        if (!$isUsernameValid || !$isEmailValid || !$isPasswordValid) {
            return ['success' => false, 'errors' => $this->validationService->getErrors()];
        }

        // Mettre à jour les informations dans la base de données
        $updateSuccess = $this->userModel->updateUserProfile($userId, $newUsername, $newEmail, $newPassword, $notif);
        
        if ($updateSuccess) {
            // Mettre à jour les commentaires associés avec le nouveau nom d'utilisateur
            $this->commentModel->updateUsernameInComments($userId, $newUsername);
            return ['success' => true];
        } else {
            // En cas d'échec inattendu
            $this->validationService->addError('general', "Une erreur est survenue lors de la mise à jour du profil.");
            return ['success' => false, 'errors' => $this->validationService->getErrors()];
        }
    }
}