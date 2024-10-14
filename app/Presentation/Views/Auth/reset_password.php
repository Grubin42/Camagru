<h2>Réinitialiser le mot de passe</h2>

<form action="/reset-password" method="POST" class="password-form">
    <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
    <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    
    <!-- Affichage des erreurs générales -->
    <?php if (isset($error) && !empty($error)): ?>
        <div class="error">
            <p><?= htmlspecialchars($error) ?></p>
        </div>
    <?php endif; ?>

    <label for="password">Nouveau mot de passe</label>
    <input type="password" class="password-input" name="password" required>
    <?php if (isset($errors['password'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['password'] as $passwordError): ?>
                <li><?= htmlspecialchars($passwordError) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <label for="confirm_password">Confirmez le mot de passe</label>
    <input type="password" name="confirm_password" required>
    <?php if (isset($errors['confirm_password'])): ?>
        <ul class="error-messages">
            <?php foreach ($errors['confirm_password'] as $confirmError): ?>
                <li><?= htmlspecialchars($confirmError) ?></li>
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

    <button type="submit">Réinitialiser le mot de passe</button>
</form>