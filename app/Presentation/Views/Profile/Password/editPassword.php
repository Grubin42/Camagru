<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>

<?php if ($password): ?>
    <?php
    $formPath = ROOT_PATH . 'Presentation/Views/Profile/Password/form.php';
    renderComponent(
        ROOT_PATH . 'Presentation/Views/Shared/Layout/FormPageLayout.php',
        [
            'title' => 'Modifier mot de passe',
            'componentPath' => $formPath,
            'error' => $error
        ]
    );
?>
<?php else: ?>
    <p>no passowrd</p> <!-- TODO: gestion d'erreur -->
<?php endif; ?>