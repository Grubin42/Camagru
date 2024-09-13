<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h1>Page de connexion</h1>
            <!-- Vérifier et afficher l'erreur si elle existe -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="/register" method="post" class="d-flex flex-column align-items-center">
                <!-- Username -->
                <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 350px;">
                    <label for="username" class="form-label" style="width: 150px;">Nom d'utilisateur :</label>
                    <input type="text" name="username" id="username" class="form-control" style="flex: 1;" required>
                </div>
                
                <!-- Password -->
                <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 350px;">
                    <label for="password" class="form-label" style="width: 150px;">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="form-control" style="flex: 1;" required>
                </div>
                
                <!-- Confirm Password -->
                <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 350px;">
                    <label for="password1" class="form-label" style="width: 150px;">Confirmer Mot de passe :</label>
                    <input type="password" name="password1" id="password1" class="form-control" style="flex: 1;" required>
                </div>
                
                <!-- Email -->
                <div class="mb-3 d-flex justify-content-center align-items-center" style="width: 350px;">
                    <label for="email" class="form-label" style="width: 150px;">Email :</label>
                    <input type="email" name="email" id="email" class="form-control" style="flex: 1;" required>
                </div>
                
                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-3">Valider les données</button>
            </form>
        </div>
    </div>
</div>