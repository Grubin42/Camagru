<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
</head>

<?php if ($password): ?>
    <h2>Modifier mot de passe</h2>
    <hr />
    <div class="profile-form-container">
        <form action="/profile/editPassword" method="post" class="flex-container edit-form ">
            <label for="current">Mot de passe actuel:</label>
            <input type="text" name="current" id="username">
            <label for="new-password">Nouveau mot de passe:</label>
            <input type="text" name="new-password" id="new-password" required>
            <label for="confirm-password">Confirmer le nouveau mot de passe:</label>
            <input type="text" name="confirm-password" id="confirm-password" required>
            <div class="button-container">
                <button type="submit">Enregistrer</button>
            </div>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    </div>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p> <!-- TODO: gestion d'erreur -->
<?php endif; ?>