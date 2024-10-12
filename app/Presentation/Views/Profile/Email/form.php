<?php

$inputfields = [
    ['name' => 'current', 'label' => 'Adresse Email actuelle', 'type' => 'email', 'value' => htmlspecialchars($email), 'disabled' => true],
    ['name' => 'email', 'label' => 'Nouvelle adresse Email', 'type' => 'email', 'required' => true],
];

$componentPath = ROOT_PATH . 'Presentation/Views/Shared/components/form.php';
$buttonText = 'Enregistrer';
$formAction = '/profile/editEmail';

renderComponent(
    $componentPath,
    [
        'inputFields' => $inputfields,
        'error' => $error,
        'buttonText' => $buttonText,
        'formAction' => $formAction
    ]
);