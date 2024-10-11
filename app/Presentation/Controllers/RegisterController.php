<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\RegisterService;

class RegisterController
{
    protected RegisterService $registerService;

    public function __construct()
    {
        $this->registerService = new RegisterService();
    }

    public function showRegisterForm()
    {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Register/index.php'
        ]);
    }

    public function register($username, $email, $password)
    {
        try {
            $this->registerService->register($username, $email, $password);
            header('Location: /login');
            exit();
        } catch (\Exception $e) {
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Register/index.php',
                'error' => $e->getMessage()
            ]);
            return; //TODO: check if it is necessary
        }
    }
}