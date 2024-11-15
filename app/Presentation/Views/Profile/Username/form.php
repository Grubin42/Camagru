<?php

$inputfields = [
    ['name' => 'current', 'label' => 'Nom d\'utilisateur actuel', 'type' => 'text', 'value' => htmlspecialchars($username), 'disabled' => true],
    ['name' => 'username', 'label' => 'Nouveau nom d\'utilisateur', 'type' => 'text', 'required' => true],
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