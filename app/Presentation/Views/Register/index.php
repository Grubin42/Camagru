<?php if (!empty($error)): ?>
    <div class="error">
        <p><?= htmlspecialchars($error) ?></p>
    </div>
<?php endif; ?>

<form action="/register" method="post" class="password-form">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($form_data['username'] ?? '') ?>" required>
    <?php if (isset($errors['username'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['username'] as $usernameError): ?>
                <li><?= htmlspecialchars($usernameError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>" required>
    <?php if (isset($errors['email'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['email'] as $emailError): ?>
                <li><?= htmlspecialchars($emailError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <label for="password">Mot de passe:</label>
    <input type="password" class="password-input" name="password" required>
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

    <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit? <a href="/login">Connectez-vous ici</a>.</p>