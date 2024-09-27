<h1>Modifier le profil</h1>

<?php if (!empty($errors)): ?>
    <div class="errors">
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="/edit-profile" method="POST">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>

    <label for="email">Email :</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <label for="password">Nouveau mot de passe (laissez vide si inchangé) :</label>
    <input type="password" name="password" placeholder="Mot de passe (facultatif)">

    <label for="notif">
        <input type="checkbox" name="notif" <?= $user['notif'] ? 'checked' : '' ?>> Recevoir des notifications
    </label>

    <button type="submit">Mettre à jour</button>
</form>