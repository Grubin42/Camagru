<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\ProfileService;

class ProfileController
{
    private $Profile;

    public function __construct()
    {
        $this->Profile = new ProfileService();
    }

    public function Index()
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'You must be logged in to access this page';
            header('Location: /login');
            exit();
        }

        renderView(ROOT_PATH . '/Presentation/Views/Shared/Layout.php', [
            'view' => ROOT_PATH . '/Presentation/Views/Profile/index.php',
        ]);
    }

    public function displayEditUserName()
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $username = $this->Profile->getUsername(id: $_SESSION['user']['id']);


        renderView(ROOT_PATH . '/Presentation/Views/Shared/Layout.php', [
            'view' => ROOT_PATH . 'Presentation/Views/Profile/Username/editUserName.php',
            'username' => $username,
        ]);
    }

    function updateUsername()
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $this->Profile->updateUsername(id: $_SESSION['user']['id'], username: $username);
            header('Location: /profile');
            exit();
        }
    }

    function displayEditPassword()
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $password = $this->Profile->getPassword(id: $_SESSION['user']['id']);

        renderView(ROOT_PATH . '/Presentation/Views/Shared/Layout.php', [
            'view' => ROOT_PATH . '/Presentation/Views/Profile/Password/editPassword.php',
            'password' => $password,
        ]);
    }

    function updatePassword()
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $current = $_POST['current'];
            $password = $_POST['new-password'];
            $confirmation = $_POST['confirm-password'];
            try {

                $this->Profile->updatePassword(id: $_SESSION['user']['id'], current: $current, password: $password, confirmation: $confirmation);
            } catch (\Exception $e) {
                renderView(ROOT_PATH . '/Presentation/Views/Shared/Layout.php', [
                    'view' => ROOT_PATH . '/Presentation/Views/Profile/Password/editPassword.php',
                    'password' => $password,
                    'error' => $e->getMessage(),
                ]);
                return;
            }
            header('Location: /profile');
            exit();
        }
    }

    function displayEditEmail(): void
    {
        // check if the user is logged in
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit();
        }

        $email = $this->Profile->getEmail(id: $_SESSION['user']['id']);

        renderView(ROOT_PATH . '/Presentation/Views/Shared/Layout.php', [
            'view' => ROOT_PATH . '/Presentation/Views/Profile/Email/editEmail.php',
            'email' => $email,
        ]);
    }

}