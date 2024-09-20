<?php

namespace Camagru\Infrastructure\Services;

class EmailService
{
    public function sendPasswordResetEmail($email, $resetUrl)
    {
        
        $subject = "Réinitialisation de votre mot de passe";
        $message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : $resetUrl";
        $headers = 'From: noreply@camagru.com' . "\r\n" .
                   'Reply-To: noreply@camagru.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    }
}