<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h1 class="mb-4">Bienvenue sur Camagru</h1>
        </div>
    </div>
    <div class="row align-items-start">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <div class="col-md-4 col-sm-6 mb-4"> <!-- 3 colonnes sur écran medium et 2 sur petits écrans -->
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="card-title">Posté le : <?= date('d-m-Y', strtotime($post['created_date'])) ?></small>
                                <?php if (isset($_SESSION['user'])): ?>
                                    <form method="post" action="/post/like" class="like-form" data-post-id="<?= $post['id'] ?>">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(GenerateCsrfToken()) ?>">
                                        <button class="btn btn-outline-primary btn-sm like-button rounded-circle" type="button">
                                            <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-thumb-up">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                                <path d="M7 11v8a1 1 0 0 1 -1 1h-2a1 1 0 0 1 -1 -1v-7a1 1 0 0 1 1 -1h3a4 4 0 0 0 4 -4v-1a2 2 0 0 1 4 0v5h3a2 2 0 0 1 2 2l-1 5a2 3 0 0 1 -2 2h-7a3 3 0 0 1 -3 -3" />
                                            </svg>
                                            <i class="bi bi-hand-thumbs-up"></i>
                                        </button>
                                        <span class="like-count"><?= $post['like_count'] ?> 
                                        <svg  xmlns="http://www.w3.org/2000/svg" style="color: red;"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-heart">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z" />
                                        </svg>
                                    </span>
                                    </form>
                                <?php else: ?>
                                    <span><?= $post['like_count'] ?> <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-heart"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6.979 3.074a6 6 0 0 1 4.988 1.425l.037 .033l.034 -.03a6 6 0 0 1 4.733 -1.44l.246 .036a6 6 0 0 1 3.364 10.008l-.18 .185l-.048 .041l-7.45 7.379a1 1 0 0 1 -1.313 .082l-.094 -.082l-7.493 -7.422a6 6 0 0 1 3.176 -10.215z" /></svg></span>
                                <?php endif; ?>
                            </div>
                            <div class="text-center my-3">
                                <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image" class="img-fluid">
                            </div>
                        </div>

                        <?php if (isset($_SESSION['user'])): ?>
                            <div class="card-footer bg-white rounded-bottom">
                                <button class="btn btn-link text-decoration-none" data-bs-toggle="collapse" data-bs-target="#comments-<?= $post['id'] ?>" aria-expanded="false">
                                    Voir les commentaires
                                </button>
                            </div>
                        <?php endif; ?>

                        <div class="collapse rounded-bottom" id="comments-<?= $post['id'] ?>">
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
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(GenerateCsrfToken()) ?>">
                                        <button class="btn btn-outline-secondary" type="button">Commenter</button>
                                    </div>
                                    <div class="error-message text-danger mt-2" style="display: none;"></div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center">Aucun post disponible.</p>
        <?php endif; ?>
    </div>

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
