<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/profile.css">
</head>

<?php if ($username): ?>
    <h2>Modifier mon nom d'utilisateur</h2>
    <hr />
    <div class="profile-form-container">
        <form action="/profile/editUsername" method="post" class="flex-container edit-form ">
            <label for="current">Nom d'utilisateur actuel:</label>
            <input type="text" name="current" id="username" value="<?= htmlspecialchars($username) ?>" disabled>
            <label for="username">Nouveau nom d'utilisateur:</label>
            <input type="text" name="username" id="username" required>
            <div class="button-container">
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p> <!-- TODO: gestion d'erreur -->
<?php endif; ?>
