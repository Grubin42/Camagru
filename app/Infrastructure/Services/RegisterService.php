<?php

namespace Camagru\Infrastructure\Services;

use Camagru\Core\Models\User;

class RegisterService
{
    protected User $userModel;
    protected TokenService $tokenService;
    protected ValidationService $validationService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->tokenService = new TokenService();
        $this->validationService = new ValidationService();
    }

    /**
     * Tente d'enregistrer un nouvel utilisateur.
     *
     * @param string $username
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function register(string $username, string $email, string $password): bool
    {
        $this->validationService->resetErrors();

        // Valider les champs
        $isUsernameValid = $this->validationService->validateUsername($username);
        $isEmailValid = $this->validationService->validateEmail($email);
        $isPasswordValid = $this->validationService->validatePassword($password);

        // Vérifier si l'utilisateur existe déjà
        if ($this->userModel->findByUsername($username)) {
            $this->validationService->addError('username', "Le nom d'utilisateur existe déjà.");
            $isUsernameValid = false;
        }

        if ($this->userModel->findByEmail($email)) {
            $this->validationService->addError('email', "L'email existe déjà.");
            $isEmailValid = false;
        }

        // Si des erreurs sont présentes, renvoyer false
        if (!$isUsernameValid || !$isEmailValid || !$isPasswordValid) {
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

        // En cas d'échec inattendu
        $this->validationService->addError('general', "Une erreur est survenue lors de l'inscription.");
        return false;
    }

    public function getErrors(): array
    {
        return $this->validationService->getErrors();
    }

    public function isValid(string $field): bool
    {
        return isset($this->validationService->getErrors()[$field]) === false;
    }
}