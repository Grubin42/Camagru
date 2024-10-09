<h1>Modifier le profil</h1>

<?php if (!empty($error)): ?>
    <div class="errors">
        <p><?= htmlspecialchars($error) ?></p>
    </div>
<?php endif; ?>

<form action="/edit-profile" method="POST" class="password-form">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
    <?php if (isset($errors['username'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['username'] as $usernameError): ?>
                <li><?= htmlspecialchars($usernameError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <label for="email">Email :</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
    <?php if (isset($errors['email'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['email'] as $emailError): ?>
                <li><?= htmlspecialchars($emailError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <label for="password">Nouveau mot de passe (laissez vide si inchangé) :</label>
    <input type="password" class="password-input" name="password" placeholder="Mot de passe (facultatif)">

    <?php if (isset($errors['password'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['password'] as $passwordError): ?>
                <li><?= htmlspecialchars($passwordError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Barre de progression pour la validation du mot de passe -->
    <div class="password-strength-container">
        <div class="password-strength-bar"></div>
    </div>
    <ul class="password-requirements">
        <li class="min-length invalid">Au moins 8 caractères</li>
        <li class="uppercase invalid">Une lettre majuscule</li>
        <li class="lowercase invalid">Une lettre minuscule</li>
        <li class="number invalid">Un chiffre</li>
        <li class="special invalid">Un caractère spécial</li>
    </ul>

    <label for="notif">
        <input type="checkbox" name="notif" <?= isset($user['notif']) && $user['notif'] ? 'checked' : '' ?>> Recevoir des notifications
    </label>

    <button type="submit">Mettre à jour</button>
</form>