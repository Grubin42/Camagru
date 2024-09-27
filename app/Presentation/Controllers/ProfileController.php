<?php

namespace Presentation\Controllers;

use Camagru\Infrastructure\Services\ProfileService;

class ProfileController {
    private $ProfileService;

    public function __construct() {
        $this->ProfileService = new ProfileService();
    }

    public function Index() {
        $user = $this->ProfileService->GetUser();
        
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Profile/index.php',
            'user' => $user
        ]);
        exit();   
    }
    public function editProfile() {

        $user = $this->ProfileService->GetUser(); // Récupère les informations utilisateur depuis le service
    
        renderView(__DIR__ . '/../Views/Shared/Layout.php', [
            'view' => __DIR__ . '/../Views/Profile/editProfile.php',
            'user' => $user
        ]);  
        exit();    
    }
    public function updateProfile() {
        // Récupérer les données actuelles de l'utilisateur pour la comparaison
        $currentUser = $this->ProfileService->getUser();
        $userId = $_SESSION['user']['id'];
        // Récupérer les valeurs soumises par l'utilisateur
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';
        $notif = isset($_POST['notif']) ? 1 : 0;
        $errors = [];

        // 1. Validation du username
        if (empty($username)) {
            $errors[] = "Le nom d'utilisateur est obligatoire.";
        }
        elseif (strlen($username) < 3) {
            $errors[] = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
        }

        // 2. Validation de l'email
        if (empty($email)) {
            $errors[] = "L'email est obligatoire.";
        }
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Veuillez entrer un email valide.";
        }

        // 3. Validation du mot de passe
        if (!empty($password)) {
            if ($password !== $confirmPassword) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            } elseif (!preg_match('/^(?=.*[A-Z]).{5,}$/', $password)) {
                $errors[] = 'Le mot de passe doit contenir au moins 5 caractères et au moins une majuscule.';
            }
        }

        // 4. Si des erreurs existent, on les affiche
        if (!empty($errors)) {
            // Renvoyer les erreurs dans la vue
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Profile/editProfile.php',
                'user' => $this->ProfileService->GetUser(),
                'errors' => $errors
            ]);
            exit();
        } else {
            // Appelle le service pour mettre à jour uniquement les champs fournis
            $this->ProfileService->UpdateProfile($username, $email, $password, $notif);

            $this->ProfileService->UpdateCommentsUsername($userId, $username);
            // ON REMET A JOUR LES DONNEES DANS LA SESSION
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['notif'] = $notif;

            // Redirection après la mise à jour
            renderView(__DIR__ . '/../Views/Shared/Layout.php', [
                'view' => __DIR__ . '/../Views/Profile/index.php',
                'user' => $this->ProfileService->getUser()
            ]);
            exit;
        }
    }
}