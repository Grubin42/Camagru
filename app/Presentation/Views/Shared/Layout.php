<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Camagru</title>
        <link rel="stylesheet" href="/Presentation/Assets/css/styles.css">
    </head>
    <body>
        <nav>
            <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="/profile">Dernier utilisateur</a></li>
                <!-- Ajouter d'autres liens ici -->
            </ul>
        </nav>

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