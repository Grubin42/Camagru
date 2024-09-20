<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;
use Camagru\Core\Models\PasswordReset;
use Camagru\Infrastructure\Services\MailService;

class PasswordResetService
{
    protected $userModel;
    protected $passwordResetModel;
    protected $mailService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->passwordResetModel = new PasswordReset();
        $this->mailService = new MailService();
    }

    /**
     * Envoie un lien de réinitialisation de mot de passe à l'utilisateur
     * s'il existe.
     */
    public function sendResetLink(string $email): bool
    {
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $expiresAt = (new \DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

            $this->passwordResetModel->createToken($user['id'], $token, $expiresAt);

            $resetLink = "http://localhost/reset-password?token=$token";
            // Envoyer l'email avec le lien de réinitialisation
            $this->mailService->sendPasswordReset($email, $resetLink);

            return true;
        }

        return false;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $resetRecord = $this->passwordResetModel->findByToken($token);

        if ($resetRecord && new \DateTime() < new \DateTime($resetRecord['expires_at'])) {
            $this->userModel->updatePassword($resetRecord['user_id'], $newPassword); // On passe le mot de passe non haché
            $this->passwordResetModel->deleteByToken($token);
            return true;
        }

        return false;
    }
    // Autres méthodes pour gérer la réinitialisation
}