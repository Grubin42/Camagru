<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>


<?php
$formPath = ROOT_PATH . 'Presentation/Views/Auth/Components/form.php';
renderComponent(
    ROOT_PATH . 'Presentation/Views/Shared/Layout/FormPageLayout.php',
    [
        'title' => 'Réinitialiser le mot de passe',
        'componentPath' => $formPath,
        'error' => $error
    ]
);
?>