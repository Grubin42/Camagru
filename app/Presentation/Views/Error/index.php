<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Erreur - Camagru</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="display-4 text-danger">Oups, une erreur s'est produite !</h1>
                    <p class="lead"><?= htmlspecialchars($errorMessage ?? 'Une erreur inconnue est survenue.') ?></p>

                    <?php if (isset($errorCode)): ?>
                        <p class="text-muted">Erreur code : <?= htmlspecialchars($errorCode) ?></p>
                    <?php endif; ?>

                    <a href="/" class="btn btn-primary mt-4">Retourner à l'accueil</a>
                </div>
            </div>
        </div>
    </body>
</html>