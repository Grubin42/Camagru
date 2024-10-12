<?php

$inputfields = [
    ['name' => 'current', 'label' => 'Mot de passe actuel', 'type' => 'password'],
    ['name' => 'new-password', 'label' => 'Nouveau mot de passe', 'type' => 'password'],
    ['name' => 'confirm-password', 'label' => 'Confirmer le nouveau mot de passe', 'type' => 'password'],
];

$componentPath = ROOT_PATH . 'Presentation/Views/Shared/components/form.php';
$buttonText = 'Enregistrer';
$formAction = '/profile/editPassword';

renderComponent(
    $componentPath,
    [
        'inputFields' => $inputfields,
        'error' => $error,
        'buttonText' => $buttonText,
        'formAction' => $formAction
    ]
);
