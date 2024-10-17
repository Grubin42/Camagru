<?php

namespace Camagru\Infrastructure\Services;

class ValidationService
{
    protected array $errors = [];

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
     * Valide un commentaire.
     *
     * @param string $comment
     * @return bool
     */
    public function validateComment(string $comment): bool
    {
        $valid = true;

        if (empty(trim($comment))) {
            $this->addError('comment', "Le commentaire ne peut pas être vide.");
            $valid = false;
        }

        if (strlen($comment) > 200) {
            $this->addError('comment', "Le commentaire ne peut pas dépasser 200 caractères.");
            $valid = false;
        }

        return $valid;
    }

        /**
     * Valide une image en vérifiant son type.
     *
     * @param string $dataURL La chaîne de données de l'image en base64.
     * @return bool
     */
    public function validateImage(string $dataURL): bool
    {
        // Extraire le type MIME de la Data URL
        if (preg_match('/^data:image\/(\w+);base64,/', $dataURL, $type)) {
            $imageType = strtolower($type[1]); // jpg, png, gif, etc.

            // Vérifier les types d'images autorisés
            if (!in_array($imageType, ['jpg', 'jpeg', 'gif', 'png'])) {
                $this->addError('image', "Type d'image invalide. Seuls JPG, JPEG, PNG et GIF sont autorisés.");
                return false;
            }

            return true;
        } else {
            $this->addError('image', "La donnée fournie n'est pas une image valide.");
            return false;
        }
    }

    /**
     * Valide la taille de l'image.
     *
     * @param string $dataURL La chaîne de données de l'image en base64.
     * @param int $maxSize En octets (par défaut 5 Mo).
     * @return bool
     */
    public function validateImageSize(string $dataURL, int $maxSize = 5242880): bool
    {
        // Calculer la taille de l'image en binaire
        $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $dataURL);
        $binaryData = base64_decode($base64String, true);

        if ($binaryData === false) {
            $this->addError('image', "L'image est corrompue ou mal encodée.");
            return false;
        }

        $dataSize = strlen($binaryData);

        if ($dataSize > $maxSize) {
            $this->addError('image', "La taille de l'image dépasse la limite de 5 Mo.");
            return false;
        }

        return true;
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