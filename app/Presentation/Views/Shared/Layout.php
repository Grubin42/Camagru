<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="stylesheet" href="/Presentation/Assets/css/styles.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-left">
                <a href="/" class="logo">Camagru</a>
            </div>
            <ul class="nav-center">
                <li><a href="/posts">Posts</a></li>
                <li><a href="/profile">Profile</a></li>
            </ul>
            <div class="nav-right">
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/logout" class="btn-logout">Logout</a>
                <?php else: ?>
                    <a href="/login" class="btn-login">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>

    <main>
        <?php 
        if (file_exists($view)) {
            include $view; 
        } else {
            echo "La vue spécifiée n'existe pas.";
        }
        ?> 
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> Camagru. Tous droits réservés.</p>
    </footer>
    
    <script src="/Presentation/Assets/js/scripts.js"></script>
</body>
</html>