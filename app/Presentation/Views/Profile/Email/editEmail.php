<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>

<?php if ($email): ?>
    <?php
    $formPath = ROOT_PATH . 'Presentation/Views/Profile/Email/form.php';
    renderComponent(
        ROOT_PATH . 'Presentation/Views/Shared/Layout/FormPageLayout.php',
        [
            'title' => 'Modifier mon email',
            'componentPath' => $formPath,
            'error' => $error
        ]
    );
?>
<?php else: ?>
    <p>no email</p> <!-- TODO: gestion d'erreur -->
<?php endif; ?>