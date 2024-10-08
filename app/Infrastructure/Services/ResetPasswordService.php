<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;

class ResetPasswordService
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }

    public function resetPassword(int $id,string $newPassword)
    {
        return $this->UserModel->resetPassword($id, $newPassword);
    }

    public function verificationResetPassword($newPassword, $confirmPassword)
    {
        $errors = [];
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
        if (strlen($newPassword) > 20) {
            $errors[] = 'Le mot de passe ne doit pas contenir plus de 20 caractères.';
        }
        return $errors;
    }
}