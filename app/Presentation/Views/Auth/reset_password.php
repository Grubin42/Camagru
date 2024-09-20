<h2>Réinitialiser le mot de passe</h2>

<!-- Affichage des erreurs s'il y en a -->
<?php if (isset($error)): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form action="/reset-password" method="POST">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
    
    <label for="password">Nouveau mot de passe</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm_password">Confirmez le mot de passe</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <button type="submit">Réinitialiser le mot de passe</button>
</form>