<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\ProfileService;

class ProfileController {
    private $Profile;

    public function __construct() {
        $this->Profile = new ProfileService();
    }

    // public function Index() {
    //     $profileModel = new ProfileService() ;
    //     $lastUser = $profileModel->getLastUser();
    //     renderView(__DIR__ . '/../Views/Shared/Layout.php', [
    //         'view' => __DIR__ . '/../Views/Profile/index.php',
    //         'user' => $lastUser
    //     ]);
    // }
}