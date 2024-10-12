<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>


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