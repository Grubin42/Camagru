<?php

$inputfields = [
    ['name' => 'email', 'label' => 'Adresse Email', 'type' => 'email'],
];

$componentPath = ROOT_PATH . 'Presentation/Views/Shared/components/from.php';
$buttonText = 'Envoyer le lien de réinitialisation';
$formAction = '/request-reset';

renderComponent(
    $componentPath,
    [
        'inputFields' => $inputfields,
        'error' => $error,
        'buttonText' => $buttonText,
        'formAction' => $formAction
    ]
);