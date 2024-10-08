<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;
use Camagru\Core\Models\Token;
use Camagru\Infrastructure\Services\MailService;

class TokenService
{
    protected User $userModel;
    protected Token $tokenModel;
    protected MailService $mailService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tokenModel = new Token();
        $this->mailService = new MailService();
    }

    /**
     * Envoie un lien de vérification d'email à l'utilisateur.
     */
    public function sendVerificationEmail(int $userId, string $email): bool
    {
        $token = bin2hex(random_bytes(50));
        $expiresAt = (new \DateTime())->modify('+24 hours')->format('Y-m-d H:i:s');

        // Créer un token de type 'email_verification'
        if (!$this->tokenModel->createToken($userId, $token, $expiresAt, 'email_verification')) {
            return false;
        }

        $verificationLink = "http://localhost/verify-email?token=$token";
        // Envoyer l'email avec le lien de vérification
        $this->mailService->sendEmailVerification($email, $verificationLink);

        return true;
    }

    /**
     * Vérifie le token de vérification d'email.
     */
    public function verifyEmail(string $token): bool
    {
        $tokenRecord = $this->tokenModel->findByTokenAndType($token, 'email_verification');

        if ($tokenRecord && new \DateTime() < new \DateTime($tokenRecord['expires_at'])) {
            // Mettre l'utilisateur comme vérifié
            if ($this->userModel->setVerified($tokenRecord['user_id'])) {
                // Supprimer le token après vérification
                $this->tokenModel->deleteByToken($token);
                return true;
            }
        }

        return false;
    }

    // Vous pouvez conserver les méthodes existantes pour la réinitialisation de mot de passe
    public function sendResetLink(string $email): bool
    {
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            $token = bin2hex(random_bytes(50));
            $expiresAt = (new \DateTime())->modify('+1 hour')->format('Y-m-d H:i:s');

            $this->tokenModel->createToken($user['id'], $token, $expiresAt, 'password_reset');

            $resetLink = "http://localhost/reset-password?token=$token";
            // Envoyer l'email avec le lien de réinitialisation
            $this->mailService->sendPasswordReset($email, $resetLink);

            return true;
        }

        return false;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $resetRecord = $this->tokenModel->findByTokenAndType($token, 'password_reset');

        if ($resetRecord && new \DateTime() < new \DateTime($resetRecord['expires_at'])) {
            $this->userModel->updatePassword($resetRecord['user_id'], $newPassword);
            $this->tokenModel->deleteByToken($token);
            return true;
        }

        return false;
    }
}