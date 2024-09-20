<h1>Bienvenue sur Camagru</h1>
<p>Ceci est la page d'accueil de votre application. Utilisez le menu de navigation pour explorer les fonctionnalités.</p>

<?php if (!empty($posts)): ?>
    <h2>Les 5 derniers posts</h2>
    
    <div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <div class="post-item">
            <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image">
            <div class="post-info">
                <p>Date: <?= $post['created_date'] ?></p>

                <!-- Afficher le nombre de likes pour tous les utilisateurs -->
                <div class="like-section">
                    <span class="like-count">❤️ <?= $post['like_count'] ?> Likes</span>

                    <!-- Si l'utilisateur est connecté, afficher le bouton Like -->
                    <?php if (isset($_SESSION['user'])): ?>
                        <form class="like-form" data-post-id="<?= $post['id'] ?>">
                            <button type="button" class="like-button">
                                <?php if ($post['liked_by_user']): ?>
                                    💔 Dislike
                                <?php else: ?>
                                    ❤️ Like
                                <?php endif; ?>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="/?page=<?= $currentPage - 1 ?>">Précédent</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/?page=<?= $i ?>" <?= $i == $currentPage ? 'class="active"' : '' ?>><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($currentPage < $totalPages): ?>
            <a href="/?page=<?= $currentPage + 1 ?>">Suivant</a>
        <?php endif; ?>
    </div>

<?php else: ?>
    <p>Aucun post disponible.</p>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const likeForms = document.querySelectorAll('.like-form');

    likeForms.forEach(form => {
        form.addEventListener('click', async (event) => {
            event.preventDefault();

            // Récupérer l'ID du post via data-post-id
            const postId = form.dataset.postId;
            const button = form.querySelector('.like-button');
            const likeCountSpan = form.closest('.post-item').querySelector('.like-count');

            // Envoyer la requête pour liker ou déliker le post
            try {
                const response = await fetch('/like-post', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                });

                if (response.ok) {
                    const result = await response.json();

                    // Mettre à jour le texte du bouton et le nombre de likes
                    likeCountSpan.textContent = `❤️ ${result.like_count} Likes`;

                    if (result.liked) {
                        button.textContent = '💔 Dislike';
                    } else {
                        button.textContent = '❤️ Like';
                    }
                } else if (response.status === 401) {
                    // Si l'utilisateur n'est pas connecté
                    alert('Vous devez être connecté pour liker un post.');
                } else {
                    console.error('Erreur lors de la mise à jour des likes.');
                    alert('Une erreur est survenue. Veuillez réessayer.');
                }
            } catch (error) {
                console.error('Erreur réseau:', error);
                alert('Erreur réseau. Veuillez vérifier votre connexion.');
            }
        });
    });
});
</script>