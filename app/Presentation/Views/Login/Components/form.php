<?php

$inputfields = [
    ['name' => 'username', 'label' => 'Nom d\'utilisateur', 'type' => 'text'],
    ['name' => 'password', 'label' => 'Mot de passe', 'type' => 'password']
];

$componentPath = ROOT_PATH . 'Presentation/Views/Shared/Components/from.php';
$buttonText = 'Se connecter';
$formAction = '/login';

renderComponent(
    $componentPath,
    [
        'inputFields' => $inputfields,
        'error' => $error,
        'buttonText' => $buttonText,
        'formAction' => $formAction
    ]
);
