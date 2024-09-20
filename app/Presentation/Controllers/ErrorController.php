<?php

namespace Presentation\Controllers;

class ErrorController
{

    public function showErrorPage($errorMessage, $errorCode = null)
    {
        renderView(__DIR__ . '/../Views/error.php', [
            'errorMessage' => $errorMessage,
            'errorCode' => $errorCode
        ]);
    }
}