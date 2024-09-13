<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Bienvenue sur Camagru</h1>       
            <!-- Vérification s'il y a des posts -->
            <?php if (!empty($posts)): ?>

                <?php foreach ($posts as $post): ?>
                <div class="card shadow mb-4">
                    <!-- Affichage du post -->
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title">Posté le : <?= date('d-m-Y', strtotime($post['created_date'])) ?></h5>
                            <button class="btn btn-outline-primary btn-sm">Like <i class="bi bi-hand-thumbs-up"></i></button>
                        </div>
                        <div class="text-center my-3">
                            <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image" class="img-fluid">
                        </div>
                    </div>

                    <!-- Bouton pour afficher/masquer les commentaires -->
                    <div class="card-footer bg-white">
                        <button class="btn btn-link text-decoration-none" data-bs-toggle="collapse" data-bs-target="#comments-<?= $post['id'] ?>" aria-expanded="false">
                            Voir les commentaires
                        </button>
                    </div>

                    <!-- Section des commentaires avec collapse -->
                    <div class="collapse" id="comments-<?= $post['id'] ?>">
                        <div class="card-body">
                            <!-- Liste des commentaires existants -->
                            <?php if (!empty($post['comments'])): ?>
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach ($post['comments'] as $comment): ?>
                                        <li class="list-group-item">
                                            <strong><?= $comment['author'] ?>:</strong> <?= $comment['content'] ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p>Aucun commentaire pour l'instant.</p>
                            <?php endif; ?>
                            
                            <!-- Formulaire d'ajout de commentaire -->
                            <form method="post" action="/post/add_comment">
                                <div class="input-group mb-3">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <input type="text" name="comment" class="form-control" placeholder="Ajouter un commentaire..." required>
                                    <button class="btn btn-outline-secondary" type="submit">Commenter</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php else: ?>
                <p class="text-center">Aucun post disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
