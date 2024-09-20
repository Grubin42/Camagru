<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h1>Page de connexion</h1>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                </div>
                <?php
                // Effacer le message d'erreur après l'affichage
                unset($_SESSION['error_message']);
                ?>
            <?php endif; ?>
            <form action="/login" method="post" class="d-flex flex-column align-items-center">
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
                <div class="d-flex justify-content-between align-items-center" style="width: 350px;">
                    <button type="submit" class="btn btn-primary mt-3">Se connecter</button>

                    <!-- Forgot Password Button -->
                    <a href="/forgot-password" class="btn btn-link mt-3">Mot de passe oublié ?</a>
                </div>
            </form>
        </div>
    </div>
</div>