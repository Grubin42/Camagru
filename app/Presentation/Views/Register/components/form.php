<?php

$inputfields = [
    ['name' => 'username', 'label' => 'Nom d\'utilisateur', 'type' => 'text'],
    ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
    ['name' => 'password', 'label' => 'Mot de passe', 'type' => 'password']
];

$componentPath = ROOT_PATH . 'Presentation/Views/Shared/Components/from.php';
$buttonText = 'S\'inscrire';
$formAction = '/register';

renderComponent(
    $componentPath,
    [
        'inputFields' => $inputfields,
        'error' => $error,
        'buttonText' => $buttonText,
        'formAction' => $formAction
    ]
);
