<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h1>Page de connexion</h1>

            <!-- Affichage des erreurs -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" action="/register" class="text-center"> <!-- Formulaire POST pour l'enregistrement -->
                
                <!-- Username -->
                <div class="form-group mt-3">
                    <label for="username">Nom d'utilisateur</label>
                    <input style="width: 250px;" type="text" class="form-control form-control-sm mx-auto" id="username" name="username" 
                           value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
                </div>

                <!-- Email -->
                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input style="width: 250px;" type="email" class="form-control form-control-sm mx-auto" id="email" name="email" 
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                </div>

                <!-- Password -->
                <div class="form-group mt-3">
                    <label for="password">Mot de passe</label>
                    <input style="width: 250px;" type="password" class="form-control form-control-sm mx-auto" id="password" name="password" required>
                </div>

                <!-- Confirm Password -->
                <div class="form-group mt-3">
                    <label for="confirmPassword">Confirmer Mot de passe</label>
                    <input style="width: 250px;" type="password" class="form-control form-control-sm mx-auto" id="confirmPassword" name="confirmPassword" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-sm mt-3">Valider les données</button>
            </form>
        </div>
    </div>
</div>