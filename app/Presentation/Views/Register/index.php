<?php if (!empty($error)): ?>
    <div class="error">
        <p style="color:red;"><?= $error ?></p>
    </div>
<?php endif; ?>

<form action="/register" method="post">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>

    <!-- Barre de progression pour la validation du mot de passe -->
    <div class="password-strength-container">
        <div id="password-strength-bar"></div>
    </div>
    <ul id="password-requirements">
        <li id="min-length" class="invalid">Au moins 8 caractères</li>
        <li id="uppercase" class="invalid">Une lettre majuscule</li>
        <li id="lowercase" class="invalid">Une lettre minuscule</li>
        <li id="number" class="invalid">Un chiffre</li>
        <li id="special" class="invalid">Un caractère spécial</li>
    </ul>

    <button type="submit">S'inscrire</button>
</form>
<p>Déjà inscrit? <a href="/login">Connectez-vous ici</a>.</p>

<style>
    .password-strength-container {
        margin: 10px 0;
        width: 100%;
        height: 10px;
        background-color: #e0e0e0;
        border-radius: 5px;
    }
    #password-strength-bar {
        height: 100%;
        width: 0;
        background-color: red;
        border-radius: 5px;
    }
    .valid {
        color: green;
    }
    .invalid {
        color: red;
    }
</style>
<script>
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');
    const requirements = {
        minLength: document.getElementById('min-length'),
        uppercase: document.getElementById('uppercase'),
        lowercase: document.getElementById('lowercase'),
        number: document.getElementById('number'),
        special: document.getElementById('special')
    };

    // Vérifier si un mot de passe correspond à tous les critères
    function checkPasswordStrength(password) {
        let strength = 0;

        // Vérifier longueur
        if (password.length >= 8) {
            requirements.minLength.classList.add('valid');
            requirements.minLength.classList.remove('invalid');
            strength += 20;
        } else {
            requirements.minLength.classList.add('invalid');
            requirements.minLength.classList.remove('valid');
        }

        // Vérifier majuscule
        if (/[A-Z]/.test(password)) {
            requirements.uppercase.classList.add('valid');
            requirements.uppercase.classList.remove('invalid');
            strength += 20;
        } else {
            requirements.uppercase.classList.add('invalid');
            requirements.uppercase.classList.remove('valid');
        }

        // Vérifier minuscule
        if (/[a-z]/.test(password)) {
            requirements.lowercase.classList.add('valid');
            requirements.lowercase.classList.remove('invalid');
            strength += 20;
        } else {
            requirements.lowercase.classList.add('invalid');
            requirements.lowercase.classList.remove('valid');
        }

        // Vérifier chiffre
        if (/[0-9]/.test(password)) {
            requirements.number.classList.add('valid');
            requirements.number.classList.remove('invalid');
            strength += 20;
        } else {
            requirements.number.classList.add('invalid');
            requirements.number.classList.remove('valid');
        }

        // Vérifier caractère spécial
        if (/[\W]/.test(password)) {
            requirements.special.classList.add('valid');
            requirements.special.classList.remove('invalid');
            strength += 20;
        } else {
            requirements.special.classList.add('invalid');
            requirements.special.classList.remove('valid');
        }

        return strength;
    }

    // Mettre à jour la jauge et les critères en fonction de la force du mot de passe
    passwordInput.addEventListener('input', () => {
        const password = passwordInput.value;
        const strength = checkPasswordStrength(password);

        // Mettre à jour la barre de progression
        strengthBar.style.width = `${strength}%`;

        // Modifier la couleur de la barre en fonction de la force
        if (strength < 40) {
            strengthBar.style.backgroundColor = 'red';
        } else if (strength < 80) {
            strengthBar.style.backgroundColor = 'orange';
        } else {
            strengthBar.style.backgroundColor = 'green';
        }
    });
</script>