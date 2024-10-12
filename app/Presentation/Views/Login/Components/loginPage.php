<div class="flex-column ">
    <?php  //TODO: check utility
    session_start();
    if (isset($_SESSION['error_message'])): ?>
        <div>
            <span>
                <?= htmlspecialchars($_SESSION['error_message']) ?>
            </span>
            <br>
        </div>
        <?php unset($_SESSION['error_message']); // Supprimer le message après l'affichage ?>
    <?php endif; ?>

    <?php
    $formPath = ROOT_PATH . 'Presentation/Views/Login/Components/form.php';

    renderComponent($formPath, ['error' => $error]); ?>
    <a href="/register" class="btn btn-primary">Pas encore inscrit ? Inscrivez-vous ici</a>
    <a href="/request-reset" class="btn btn-primary">Mot de passe oublié ?</a>
</div>