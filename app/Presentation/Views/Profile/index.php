<div class="container-sm">
    <div class="card shadow mt-5">
        <div class="card-body text-center my-auto">
            <h2>Vos informations</h2>
            <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Notif Active :</strong> 
                <?= $user['notif'] ? 'Oui' : 'Non'; ?> <!-- Affichage conditionnel du boolean -->
            </p>
            <!-- Bouton pour rediriger vers la page de modification -->
            <a href="/editProfile" class="btn btn-primary btn-sm mt-3">Modifier vos informations</a>
        </div>  
    </div>
</div>