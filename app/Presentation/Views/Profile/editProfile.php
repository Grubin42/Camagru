<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h2>Modifier vos informations</h2>

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

            <form method="POST" action="/updateProfile" class="text-center"> <!-- Formulaire POST pour la mise à jour -->
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(GenerateCsrfToken()) ?>">
                <div class="form-group mt-3">
                    <label for="username">Nom d'utilisateur</label>
                    <input style="width: 250px;" type="text" class="form-control form-control-sm mx-auto" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>">
                    <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas le modifier.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="email">Email</label>
                    <input style="width: 250px;" type="email" class="form-control form-control-sm mx-auto" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                    <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas le modifier.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="password">Nouveau mot de passe</label>
                    <input style="width: 250px;" type="password" class="form-control form-control-sm mx-auto" id="password" name="password">
                    <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer votre mot de passe.</small>
                </div>

                <div class="form-group mt-3">
                    <label for="confirm_password">Confirmez le mot de passe</label>
                    <input style="width: 250px;" type="password" class="form-control form-control-sm mx-auto" id="confirm_password" name="confirm_password">
                </div>
                <!-- Checkbox pour activer/désactiver les notifications -->
                <div class="form-group mt-3">
                    <label for="notif">Notifications Actives</label><br>
                    <input type="checkbox" id="notif" name="notif" value="1" <?= $user['notif'] ? 'checked' : '' ?>> Activer les notifications
                </div>
                
                <button type="submit" class="btn btn-primary btn-sm mt-3">Enregistrer les modifications</button>
            </form>
        </div>  
    </div>
</div>