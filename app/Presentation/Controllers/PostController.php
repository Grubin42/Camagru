<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\PostService;

class PostController {
    private $PostService;

    public function __construct() {
        $this->PostService = new PostService();
    }



    public function Index() {
        $posts = $this->PostService->GetMyImage();

        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Post/index.php',
            'posts' => $posts
        ]);
    }

    public function ImageRegister(string $image) {
        $this->PostService->ImageRegister($image);

        header('Location: /');
        exit; 
    }

    public function SavePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Vérifier si les champs sont bien envoyés
            if (isset($_POST['photo']) && isset($_POST['sticker'])) {
                $photoData = $_POST['photo'];  // Photo capturée en base64
                $stickerData = $_POST['sticker'];  // Sticker en base64
                
                // Appeler le service pour fusionner et sauvegarder l'image
                $result = $this->PostService->mergeAndSaveImage(photoData: $photoData, stickerData: $stickerData);
        
                if ($result) {
                    // Rediriger vers une page de succès ou afficher un message de succès
                    header('Location: /');
                    exit(); // Terminer l'exécution après la redirection
                } else {
                    // Rediriger vers une page d'erreur ou afficher un message d'erreur
                    header('Location: /post/error');
                    exit(); // Terminer l'exécution après la redirection
                }
            } else {
                // Gérer le cas où les données sont manquantes
                echo "Erreur : données photo ou sticker manquantes.";
            }
        } else {
            // Gérer le cas où la requête n'est pas en POST
            echo "Méthode de requête invalide.";
        }
    }
}
