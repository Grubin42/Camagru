<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>


<?php
$formPath = ROOT_PATH . 'Presentation/Views/Register/components/form.php';
renderComponent(
    __DIR__ . '/../Shared/Layout/FormPageLayout.php',
    [
        'title' => 'Inscription',
        'componentPath' => $formPath,
        'error' => $error
    ]
);
?>