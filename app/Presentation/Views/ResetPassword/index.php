<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h2>Réinitialisation du mot de passe</h2>

                    <!-- Afficher les erreurs si elles existent -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Formulaire de réinitialisation du mot de passe -->
                    <form action="/reset-password" method="post">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token']) ?>">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(GenerateCsrfToken()) ?>">
                        <div class="mb-3">
                            <label for="password" class="form-label">Nouveau mot de passe :</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirmer le mot de passe :</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Réinitialiser</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>