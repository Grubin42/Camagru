<h2>Connexion</h2>

<!-- Affichage des erreurs depuis la session s'il y en a -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= $_SESSION['error_message']; ?>
        <br>
        <a href="/register" class="btn btn-primary">Pas encore inscrit ? Inscrivez-vous ici</a>
    </div>
    <?php unset($_SESSION['error_message']); // Supprimer le message après l'affichage ?>
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