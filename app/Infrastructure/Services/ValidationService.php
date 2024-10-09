<?php

namespace Camagru\Infrastructure\Services;

class ValidationService
{
    protected array $errors = [];

    // ... (autres méthodes de validation) ...

    /**
     * Ajoute une erreur pour un champ spécifique.
     *
     * @param string $field
     * @param string $message
     */
    public function addError(string $field, string $message): void
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    /**
     * Valide un nom d'utilisateur.
     *
     * @param string $username
     * @return bool
     */
    public function validateUsername(string $username): bool
    {
        if (strlen($username) < 3 || strlen($username) > 20) {
            $this->addError('username', "Le nom d'utilisateur doit contenir entre 3 et 20 caractères.");
            return false;
        }
        return true;
    }

    /**
     * Valide une adresse email.
     *
     * @param string $email
     * @return bool
     */
    public function validateEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', "L'adresse email est invalide.");
            return false;
        }
        return true;
    }

    /**
     * Valide un mot de passe selon des critères définis.
     *
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password): bool
    {
        $valid = true;

        if (strlen($password) < 8) {
            $this->addError('password', "Au moins 8 caractères.");
            $valid = false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            $this->addError('password', "Une lettre majuscule.");
            $valid = false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            $this->addError('password', "Une lettre minuscule.");
            $valid = false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            $this->addError('password', "Un chiffre.");
            $valid = false;
        }
        if (!preg_match('/[\W]/', $password)) {
            $this->addError('password', "Un caractère spécial.");
            $valid = false;
        }

        if (!$valid) {
            $this->addError('password', "Le mot de passe ne répond pas aux critères de sécurité.");
        }

        return $valid;
    }

    /**
     * Récupère les erreurs de validation.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Réinitialise les erreurs de validation.
     */
    public function resetErrors(): void
    {
        $this->errors = [];
    }
}