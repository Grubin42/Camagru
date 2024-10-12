<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>

<!-- Lien vers la page de demande de réinitialisation de mot de passe -->
<!-- <p>
    <a href="/request-reset">Mot de passe oublié ?</a>
</p> -->



<?php
$formPath = ROOT_PATH . 'Presentation/Views/Login/Components/loginPage.php';
renderComponent(
    __DIR__ . '/../Shared/Layout/FormPageLayout.php',
    [
        'title' => 'Connexion',
        'componentPath' => $formPath,
        'error' => $error
    ]
);
?>