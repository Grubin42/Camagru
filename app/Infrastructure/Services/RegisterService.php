<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;

class RegisterService
{
    protected User $userModel;
    protected TokenService $tokenService;
    protected array $errors = [];
    protected array $validFields = [];


    public function __construct()
    {
        $this->userModel = new User();
        $this->tokenService = new TokenService();
    }

    public function register(string $username, string $email, string $password): bool
    {
        $this->validateUsername($username);
        $this->validateEmail($email);
        $this->validatePassword($password);

        // Si des erreurs sont présentes, renvoyer false
        if (!empty($this->errors)) {
            return false;
        }

        // Vérifier si l'utilisateur existe déjà
        if ($this->userModel->findByUsername($username) || $this->userModel->findByEmail($email)) {
            $this->errors[] = "Le nom d'utilisateur ou l'email existe déjà.";
            return false;
        }

        // Création de l'utilisateur
        if ($this->userModel->createUser($username, $email, $password)) {
            $user = $this->userModel->findByEmail($email);
            if ($user) {
                // Envoyer l'email de vérification
                return $this->tokenService->sendVerificationEmail($user['id'], $email);
            }
        }

        return false;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function isValid(string $field): bool
    {
        return $this->validFields[$field] ?? false;
    }

    // Méthode pour valider le nom d'utilisateur
    private function validateUsername(string $username)
    {
        if (strlen($username) < 3 || strlen($username) > 20) {
            $this->errors[] = "Le nom d'utilisateur doit contenir entre 3 et 20 caractères.";
        } else {
            $this->validFields['username'] = true;
        }
    }

    // Méthode pour valider l'email
    private function validateEmail(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "L'adresse email est invalide.";
        } else {
            $this->validFields['email'] = true;
        }
    }

    // Méthode pour valider le mot de passe
    private function validatePassword(string $password)
    {
        if (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password) || 
            !preg_match('/[\W]/', $password)) {
            $this->errors[] = "Le mot de passe doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } else {
            $this->validFields['password'] = true;
        }
    }
}