<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>

<?php if ($username): ?>
    <?php
    $formPath = ROOT_PATH . 'Presentation/Views/Profile/Username/form.php';
    renderComponent(
        ROOT_PATH . 'Presentation/Views/Shared/Layout/FormPageLayout.php',
        [
            'title' => 'Modifier mon nom d\'utilisateur',
            'componentPath' => $formPath,
            'error' => $error
        ]
    );
?>
<?php else: ?>
    <p>no username</p> <!-- TODO: gestion d'erreur -->
<?php endif; ?>