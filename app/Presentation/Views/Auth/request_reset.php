<h2>Réinitialiser le mot de passe</h2>

<!-- Affichage des messages de succès ou des erreurs s'il y en a -->
<?php if (isset($success)): ?>
    <p class="success"><?= $success ?></p>
<?php elseif (isset($error)): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form action="/request-reset" method="POST">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <label for="email">Adresse Email</label>
    <input type="email" id="email" name="email" required>
    <button type="submit">Envoyer le lien de réinitialisation</button>
</form>

<style>
    .success {
        color: green;
    }
    .error {
        color: red;
    }
</style>