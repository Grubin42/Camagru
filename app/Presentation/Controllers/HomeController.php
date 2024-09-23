<?php

namespace Presentation\Controllers;


use Camagru\Infrastructure\Services\HomeService;

use Camagru\Infrastructure\Services\PostService;

class HomeController {
   // private $Home_service;

   private $postService;

   public function __construct() {
         $this->postService = new PostService();
   }

    public function Index() {

         // check if the user is logged in
         if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // TODO: pass a variable to the view to dispaly a message -< please first login
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Veuillez d\'abord vous connecter.';
            header('Location: /login');
            exit();
        }

        $posts = $this->postService->showAllPost(); 

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Home/index.php',
            'posts' => $posts,
        ]);
    }
}
