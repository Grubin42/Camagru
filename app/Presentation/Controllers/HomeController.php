<?php

namespace Presentation\Controllers;


use Camagru\Infrastructure\Services\HomeService;

class HomeController {
   // private $Home_service;

   // public function __construct() {
   //     $this->Home_service = new HomeService();
   // }

    public function Index() {
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php'
        ]);
    }
}
