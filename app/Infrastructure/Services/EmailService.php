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
    public function sendCommentNotification($email, $username, $created_date)
    {
        $subject = "Nouveau commentaire sur votre post";
        $message = "Bonjour,\n\n$username a laisse un nouveau commentaire sur votre post cree le $created_date.\n\nConsultez votre post pour voir ce nouveau commentaire.";
        $headers = 'From: noreply@camagru.com' . "\r\n" .
                   'Reply-To: noreply@camagru.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    }

    public function sendVerificationEmail($email, $token) {
        $verificationUrl = "https://localhost/verify?token=$token";
        $subject = "Validation de votre compte";
        $message = "Cliquez sur le lien suivant pour valider votre compte : $verificationUrl";
        $headers = 'From: noreply@camagru.com' . "\r\n" .
                   'Reply-To: noreply@camagru.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    }
}