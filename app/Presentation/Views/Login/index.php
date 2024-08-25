<h2>Connexion</h2>

<!-- Affichage des erreurs s'il y en a -->
<?php if (isset($error)): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form action="/login" method="POST">
    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Mot de passe</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>

<!-- Lien vers la page de demande de réinitialisation de mot de passe -->
<p>
    <a href="/request-reset">Mot de passe oublié ?</a>
</p>