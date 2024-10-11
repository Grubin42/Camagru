<head>
    <link rel="stylesheet" href="/Presentation/Assets/css/register.css">
    <link rel="stylesheet" href="/Presentation/Assets/css/shared.css">
</head>

<h1>Inscription</h1>
<hr />
<form action="/register" method="post" class="flex-column-gap-10 flex-center-all">
    <div class="flex-column">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="flex-column">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="flex-column">
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
    </div>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit? <a href="/login">Connectez-vous ici</a>.</p>