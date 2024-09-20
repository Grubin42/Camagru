<h1>Inscription</h1>
<form action="/register" method="post">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit? <a href="/login">Connectez-vous ici</a>.</p>