<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Camagru</title>
    <link rel="preload" href="/Presentation/Assets/css/main.css" as="style" onload="this.rel='stylesheet'">
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
                <!-- Bouton Hamburger -->
                <button class="hamburger" id="hamburger-btn" aria-label="Ouvrir le menu">
                    ☰ <!-- Symbole hamburger -->
                </button>
                <!-- Menu Déroulant -->
                <div class="side-menu" id="side-menu">
                    <button class="close-btn" id="close-btn" aria-label="Fermer le menu">×</button>
                    <ul class="menu-links">
                        <?php if (isset($_SESSION['user'])): ?>
                            <li><a href="/posts">Posts</a></li>
                            <li><a href="/profile">Profile</a></li>
                            <li><a href="/logout" class="btn-logout">Logout</a></li>
                        <?php else: ?>
                            <li><a href="/login" class="btn-login">Login</a></li>
                            <li><a href="/register" class="btn-register">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="overlay" id="overlay"></div>

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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const hamburgerBtn = document.getElementById('hamburger-btn');
            const sideMenu = document.getElementById('side-menu');
            const closeBtn = document.getElementById('close-btn');
            const overlay = document.getElementById('overlay');

            // Ouvrir le menu
            hamburgerBtn.addEventListener('click', function () {
                sideMenu.classList.add('open');
                overlay.classList.add('show');
                hamburgerBtn.style.display = 'none'; // Masquer le hamburger
                // Empêcher le scroll
                document.body.classList.add('no-scroll');
            });

            // Fermer le menu
            function closeMenu() {
                sideMenu.classList.remove('open');
                overlay.classList.remove('show');
                hamburgerBtn.style.display = 'block'; // Réafficher le hamburger
                // Réactiver le scroll
                document.body.classList.remove('no-scroll');
            }

            closeBtn.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);

            // Fermer le menu en appuyant sur Échap
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && sideMenu.classList.contains('open')) {
                    closeMenu();
                }
            });
        });
    </script>
</body>
</html>