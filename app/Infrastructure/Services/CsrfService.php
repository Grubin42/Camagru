<?php

namespace Camagru\Infrastructure\Services;

class CsrfService
{
    protected string $sessionKey = '_csrf_token';

    /**
     * Génère un token CSRF et le stocke dans la session.
     *
     * @return string
     */
    public function generateToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $token = bin2hex(random_bytes(32));
        $_SESSION[$this->sessionKey] = $token;
        return $token;
    }

    /**
     * Récupère le token CSRF actuel, en génère un si nécessaire.
     *
     * @return string
     */
    public function getToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[$this->sessionKey])) {
            return $this->generateToken();
        }

        return $_SESSION[$this->sessionKey];
    }

    /**
     * Valide un token CSRF donné.
     *
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION[$this->sessionKey])) {
            return false;
        }

        return hash_equals($_SESSION[$this->sessionKey], $token);
    }

    /**
     * Supprime le token CSRF de la session.
     */
    public function removeToken(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        unset($_SESSION[$this->sessionKey]);
    }
}