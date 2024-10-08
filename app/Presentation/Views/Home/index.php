<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Bienvenue sur Camagru</h1>
            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title">Posté le : <?= date('d-m-Y', strtotime($post['created_date'])) ?></h6>
                                <?php if (isset($_SESSION['user'])): ?>
                                    <form method="post" action="/post/like" class="like-form" data-post-id="<?= $post['id'] ?>">
                                        <button class="btn btn-outline-primary btn-sm like-button" type="button">
                                            Like <i class="bi bi-hand-thumbs-up"></i>
                                        </button>
                                        <span class="like-count"><?= $post['like_count'] ?> likes</span>
                                    </form>
                                <?php else: ?>
                                    <span><?= $post['like_count'] ?> likes</span>
                                <?php endif; ?>
                            </div>
                            <div class="text-center my-3">
                                <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image" class="img-fluid">
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="card-footer bg-white">
                                <button class="btn btn-link text-decoration-none" data-bs-toggle="collapse" data-bs-target="#comments-<?= $post['id'] ?>" aria-expanded="false">
                                    Voir les commentaires
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="collapse" id="comments-<?= $post['id'] ?>">
                            <div class="card-body">
                                <ul class="list-group list-group-flush mb-3 comment-list" id="comment-list-<?= $post['id'] ?>">
                                    <?php if (!empty($post['comments'])): ?>
                                        <?php foreach ($post['comments'] as $comment): ?>
                                            <li class="list-group-item">
                                                <strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['commentaire']) ?>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <li class="list-group-item"></li>
                                    <?php endif; ?>
                                </ul>

                                <form method="post" action="/post/add_comment" class="comment-form" data-post-id="<?= $post['id'] ?>">
                                    <div class="input-group mb-3">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <input type="text" name="comment" class="form-control" placeholder="Ajouter un commentaire..." required>
                                        <button class="btn btn-outline-secondary" type="button">Commenter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <nav class="nav-pagi">
                    <ul class="pagination justify-content-center">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Précédent">Précédent</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Suivant">Suivant</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php else: ?>
                <p class="text-center">Aucun post disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .nav-pagi{
        background: none;
    }
    
    .pagination .page-link:focus, 
    .pagination .page-link:hover {
        outline: none;
        box-shadow: none;
        color: #000; 
        text-decoration: none;
    }

    .pagination .page-item.active .page-link {
        color: #000;
        font-weight: bold;
        text-decoration: underline;
    }

    .pagination {
        justify-content: center;
    }
</style>

<script src="/Presentation/Assets/js/likecomment.js"></script>
