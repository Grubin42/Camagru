<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h2>Réinitialisation du mot de passe</h2>
                    <p>Entrez votre email pour recevoir un lien de réinitialisation.</p>
                    <form action="/forgot-password" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse email :</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Envoyer le lien</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>