document.addEventListener('DOMContentLoaded', () => {
    const passwordForms = document.querySelectorAll('.password-form');

    passwordForms.forEach(form => {
        const passwordInput = form.querySelector('.password-input');
        const strengthBar = form.querySelector('.password-strength-bar');
        const requirementsList = form.querySelector('.password-requirements');

        if (!passwordInput || !strengthBar || !requirementsList) {
            return; // S'assurer que tous les éléments nécessaires sont présents
        }

        const requirements = {
            minLength: requirementsList.querySelector('.min-length'),
            uppercase: requirementsList.querySelector('.uppercase'),
            lowercase: requirementsList.querySelector('.lowercase'),
            number: requirementsList.querySelector('.number'),
            special: requirementsList.querySelector('.special')
        };

        // Fonction de vérification de la force du mot de passe
        function checkPasswordStrength(password) {
            let strength = 0;

            // Vérifier la longueur
            if (password.length >= 8) {
                requirements.minLength.classList.add('valid');
                requirements.minLength.classList.remove('invalid');
                strength += 20;
            } else {
                requirements.minLength.classList.add('invalid');
                requirements.minLength.classList.remove('valid');
            }

            // Vérifier les majuscules
            if (/[A-Z]/.test(password)) {
                requirements.uppercase.classList.add('valid');
                requirements.uppercase.classList.remove('invalid');
                strength += 20;
            } else {
                requirements.uppercase.classList.add('invalid');
                requirements.uppercase.classList.remove('valid');
            }

            // Vérifier les minuscules
            if (/[a-z]/.test(password)) {
                requirements.lowercase.classList.add('valid');
                requirements.lowercase.classList.remove('invalid');
                strength += 20;
            } else {
                requirements.lowercase.classList.add('invalid');
                requirements.lowercase.classList.remove('valid');
            }

            // Vérifier les chiffres
            if (/[0-9]/.test(password)) {
                requirements.number.classList.add('valid');
                requirements.number.classList.remove('invalid');
                strength += 20;
            } else {
                requirements.number.classList.add('invalid');
                requirements.number.classList.remove('valid');
            }

            // Vérifier les caractères spéciaux
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

        // Écouter les événements d'entrée sur le champ de mot de passe
        passwordInput.addEventListener('input', () => {
            const password = passwordInput.value;
            const strength = checkPasswordStrength(password);

            // Mettre à jour la barre de progression
            strengthBar.style.width = `${strength}%`;

            // Changer la couleur de la barre en fonction de la force
            if (strength < 40) {
                strengthBar.style.backgroundColor = 'red';
            } else if (strength < 80) {
                strengthBar.style.backgroundColor = 'orange';
            } else {
                strengthBar.style.backgroundColor = 'green';
            }
        });
    });
});