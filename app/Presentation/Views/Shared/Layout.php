<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="/home">Home</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <?php include __DIR__ . '/../Home/index.php'; ?>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Votre Application. Tous droits réservés.</p>
        <script src="/assets/js/scripts.js"></script>
    </footer>
</body>
</html>