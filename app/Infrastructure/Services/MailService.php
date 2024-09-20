<?php

namespace Camagru\Infrastructure\Services;

class MailService
{
    public function sendPasswordReset(string $email, string $resetLink)
    {
        $subject = 'Réinitialisation de votre mot de passe';
        $message = "Cliquez sur ce lien pour réinitialiser votre mot de passe : $resetLink";
        $headers = 'From: no-reply@camagru.com' . "\r\n" .
                   'Reply-To: no-reply@camagru.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();

        mail($email, $subject, $message, $headers);
    }

    public function sendCommentNotification(string $email, string $commenter, int $postId)
    {
        $subject = 'Nouveau commentaire sur votre post';
        $message = "$commenter a commenté votre post $postId";
        $headers = 'From: no-reply@camagru.com' . "\r\n" .
                   'Reply-To: no-reply@camagru.com' . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
    
        mail($email, $subject, $message, $headers);
    }
}