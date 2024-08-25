<div class="container-sm">
        <div class="card shadow mt-5">
            <div class="card-body text-center my-auto">
                <h1>Page de connexion</h1>
                <form action="/register" method="post">
                    <div class="mb-3">
                        <label for="username">Nom d'utilisateur :</label>
                        <input type="text" name="username" id="username" class="form-control-m m-2" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password" class="form-control-m m-2" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Mot de passe :</label>
                        <input type="password" name="password" id="password1" class="form-control-m m-2" required>
                    </div>
                    <div class="mb-3">
                        <label for="password">Email :</label>
                        <input type="email" name="email" id="email" class="form-control-m m-2" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Valider les données</button>
                </form>
            </div>
        </div>
    </div>