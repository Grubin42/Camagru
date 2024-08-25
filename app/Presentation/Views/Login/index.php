<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h1>Page de connexion</h1>
            <form action="/login" method="post">
                <div class="mb-3">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" name="username" id="username" class="form-control-m m-2" required>
                </div>
                <div class="mb-3">
                    <label for="password">Mot de passe :</label>
                    <input type="password" name="password" id="password" class="form-control-m m-2" required>
                </div>
                <button type="submit" class="btn btn-primary">Se connecter</button>
            </form>
        </div>
    </div>
</div>