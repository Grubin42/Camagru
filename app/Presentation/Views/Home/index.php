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

                <!-- Accordéon pour les commentaires -->
                <div class="comment-section">
                    <button class="toggle-comments">Voir les commentaires (<?= count($post['comments']) ?>)</button>
                    <div class="comments" style="display: none;">
                        <?php if (!empty($post['comments'])): ?>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <p>
                                    <strong><?= $comment['username'] ?></strong> : <?= $comment['commentaire'] ?>
                                    <small>(<?= $comment['created_date'] ?>)</small>
                                </p>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Aucun commentaire pour ce post.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Si l'utilisateur est connecté, afficher le formulaire de commentaire -->
                <?php if (isset($_SESSION['user'])): ?>
                    <form class="comment-form" data-post-id="<?= $post['id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <textarea name="comment" placeholder="Écrire un commentaire..."></textarea>
                        <button type="submit">Commenter</button>
                    </form>
                <?php endif; ?>

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
    // Gestion des likes
    const likeForms = document.querySelectorAll('.like-form');

    likeForms.forEach(form => {
        form.addEventListener('click', async (event) => {
            event.preventDefault();

            const postId = form.dataset.postId;
            const button = form.querySelector('.like-button');
            const likeCountSpan = form.closest('.post-item').querySelector('.like-count');

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
                    likeCountSpan.textContent = `❤️ ${result.like_count} Likes`;

                    if (result.liked) {
                        button.textContent = '💔 Dislike';
                    } else {
                        button.textContent = '❤️ Like';
                    }
                } else if (response.status === 401) {
                    alert('Vous devez être connecté pour liker un post.');
                } else {
                    alert('Erreur lors de la mise à jour des likes.');
                }
            } catch (error) {
                alert('Erreur réseau. Veuillez vérifier votre connexion.');
            }
        });
    });

    // Gestion des commentaires
    const commentForms = document.querySelectorAll('.comment-form');

    commentForms.forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault(); // Empêcher le rechargement de la page

            const formData = new FormData(form);
            const postId = formData.get('post_id');
            const commentSection = document.getElementById(`comments-list-${postId}`);

            try {
                const response = await fetch('/add-comment', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.username && result.comment) {
                        // Ajouter le nouveau commentaire dans la section
                        const newComment = `<p><strong>${result.username}</strong> : ${result.comment}</p>`;
                        commentSection.insertAdjacentHTML('beforeend', newComment);
                        form.reset(); // Réinitialiser le formulaire après soumission
                    }
                } else if (response.status === 401) {
                    alert('Vous devez être connecté pour commenter.');
                } else {
                    alert('Erreur lors de l\'ajout du commentaire.');
                }
            } catch (error) {
                alert('Erreur réseau. Veuillez vérifier votre connexion.');
            }
        });
    });

    const toggleButtons = document.querySelectorAll('.toggle-comments');

    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const commentsDiv = button.nextElementSibling;
            commentsDiv.style.display = (commentsDiv.style.display === 'none' || commentsDiv.style.display === '') ? 'block' : 'none';
        });
    });
});
</script>