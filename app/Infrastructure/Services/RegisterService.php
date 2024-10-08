<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\UserModel;
class RegisterService
{
    private $UserModel;

    public function __construct()
    {
        $this->UserModel = new UserModel();
    }
    public function RegisterUser(string $username, $password, $email)  
    {
        return $this->UserModel->RegisterUser($username, $password, $email);
    }
    public function isUsernameTaken(string $username): bool {
        return $this->UserModel->isUsernameTaken($username);
    }
    public function findUserByToken($token) {
        return $this->UserModel->findUserByToken($token);
    }
    
    public function verifyUser($userId) {
        return $this->UserModel->verifyUser($userId);
    }

    public function verificationRegister($username, $email, $password, $confirmPassword)
    {
        $errors = [];
        // Vérifier si les mots de passe correspondent
        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
            elseif (strlen($password) > 20) {
                $errors[] = 'Le mot de passe ne doit pas contenir plus de 20 caractères.';
            }
            elseif (!preg_match('/^(?=.*[A-Z]).{5,}$/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins 5 caractères et au moins une majuscule.';
            }
        }
    
        // Vérifier si le nom d'utilisateur est déjà utilisé
        if ($this->isUsernameTaken($username)) {
            $errors[] = 'Le nom d\'utilisateur est déjà pris.';
        }
        elseif (strlen(string: $username) > 30) {
            $errors[] = 'Le login ne doit pas contenir plus de 30 caractères.';
        }

        if (empty($email)) {
            $errors[] = "L'email est obligatoire.";
        }
        elseif (strlen($email) > 50) {
            $errors[] = 'Le mail ne doit pas contenir plus de 50 caractères.';
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Veuillez entrer un email valide.";
        }
        return $errors;
    }
}