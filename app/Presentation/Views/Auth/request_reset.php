<h2>Réinitialiser le mot de passe</h2>

<!-- Affichage des erreurs s'il y en a -->
<?php if (isset($error)): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form action="/request-reset" method="POST">
    <label for="email">Adresse Email</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Envoyer le lien de réinitialisation</button>
</form>