<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="/Presentation/Assets/css/main.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <a href="/" class="logo">Camagru</a>
            </div>
            <ul class="nav-center">
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="/posts">Posts</a></li>
                    <li><a href="/profile">Profile</a></li>
                <?php endif; ?>
            </ul>
            <div class="nav-right">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/logout" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn-login">Login</a>
                    <a href="/register" class="btn-register">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <!-- Affichage des messages de vérification de l'email -->
        <?php if (isset($_GET['verified'])): ?>
            <?php if ($_GET['verified'] === 'success'): ?>
                <div class="alert alert-success">
                    Votre adresse email a été vérifiée avec succès ! Vous pouvez maintenant vous connecter.
                </div>
            <?php elseif ($_GET['verified'] === 'error'): ?>
                <div class="alert alert-danger">
                    Le lien de vérification est invalide ou a expiré.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Inclusion de la vue spécifique -->
        <?php 
        if (isset($view) && file_exists($view)) {
            include $view; 
        } else {
            echo "La vue spécifiée n'existe pas.";
        }
        ?> 
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Camagru. Tous droits réservés.</p>
    </footer>
    
    <script src="/Presentation/Assets/js/password-strength.js"></script>
</body>
</html>