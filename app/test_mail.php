<?php
$to = 'tonadresse@example.com';
$subject = 'Test Email';
$message = 'This is a test email.';
$headers = 'From: hello@camagru.com' . "\r\n" .
           'Reply-To: hello@camagru.com' . "\r\n" .
           'X-Mailer: PHP/' . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo 'Email sent successfully.';
} else {
    echo 'Failed to send email.';
}