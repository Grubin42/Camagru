<h1>Connexion</h1>
<form action="/login" method="post">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Se connecter</button>
</form>
<p>Pas encore de compte? <a href="/register">Inscrivez-vous ici</a>.</p>