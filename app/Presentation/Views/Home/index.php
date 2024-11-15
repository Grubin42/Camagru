<!-- /Presentation/Views/Home/index.php -->
<h1>Bienvenue sur Camagru</h1>

<?php if (!empty($posts)): ?>    
    <div class="posts-grid">
    <?php foreach ($posts as $post): ?>
        <div class="post-item">
            <div class="image-section">
                <img src="data:image/png;base64,<?= htmlspecialchars($post['image']) ?>" alt="Post Image">
                <span class="like-count">❤️ <?= htmlspecialchars($post['like_count']) ?> Likes</span>
                <?php
                $date = new DateTime($post['created_date']);
                ?>
                <p><?= $date->format('Y-m-d') ?></p>
            </div>

            <!-- Afficher le nombre de likes pour tous les utilisateurs -->
            <div class="like-section">
                <?php if (isset($_SESSION['user'])): ?>
                    <form class="like-form" data-post-id="<?= htmlspecialchars($post['id']) ?>">
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


            <!-- Section des commentaires -->
            <div class="comments-section">
                <div class="comments-header">
                    <!-- <span id="comment-count-<?= htmlspecialchars($post['id']) ?>"><?= count($post['comment']) ?></span> commentaires -->
                </div>
                <div id="comments-list-<?= htmlspecialchars($post['id']) ?>" class="comments">
                    <?php if (!empty($post['comment'])): ?>
                        <?php foreach ($post['comment'] as $comment): ?>
                            <p>
                                <strong><?= htmlspecialchars($comment['username']) ?></strong> : <?= htmlspecialchars($comment['comment']) ?>
                                <small>
                                <?php
                                    $date = new DateTime($comment['created_date']);
                                ?>
                                    (<?= $date->format('Y-m-d') ?>)
                                </small>
                            </p>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-comments">Aucun commentaire pour ce post.</p>
                    <?php endif; ?>
                </div>

                <!-- Formulaire de commentaire -->
                <?php if (isset($_SESSION['user'])): ?>
                    <form class="comment-form" data-post-id="<?= htmlspecialchars($post['id']) ?>">
                        <input type="hidden" name="post_id" value="<?= htmlspecialchars($post['id']) ?>">
                        <textarea name="comment" placeholder="Écrire un commentaire..." maxlength="200" required></textarea>
                        <div class="comment-errors"></div> <!-- Conteneur pour les erreurs de commentaire -->
                        <button type="submit">Commenter</button>
                    </form>
                <?php endif; ?>
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

<!-- Inclure le CSRF Token comme une variable JavaScript -->
<script>
    const CSRF_TOKEN = "<?= htmlspecialchars($csrf_token) ?>";
</script>

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
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': CSRF_TOKEN,
                        'X-Requested-With': 'XMLHttpRequest'
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
            const comment = formData.get('comment'); // Récupérer le commentaire
            const commentSection = document.getElementById(`comments-list-${postId}`);
            const commentErrorsDiv = form.querySelector('.comment-errors'); // Récupérer le conteneur des erreurs

            // Réinitialiser les erreurs précédentes
            commentErrorsDiv.innerHTML = '';

            try {
                const response = await fetch('/add-comment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json', 
                        'X-CSRF-Token': CSRF_TOKEN ,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        comment: comment
                    })
                });

                if (response.ok) {
                    const result = await response.json();
                    if (result.username && result.comment) {

                        const noComments = commentSection.querySelector('.no-comments');
                        if (noComments) {
                            noComments.remove();
                        }
                        // Ajouter le nouveau commentaire dans la section
                        const newComment = `<p><strong>${result.username}</strong> : ${result.comment}</p>`;
                        commentSection.insertAdjacentHTML('beforeend', newComment);
                        form.reset(); // Réinitialiser le formulaire après soumission
                    }
                } else if (response.status === 400) {
                    const result = await response.json();
                    if (result.errors && result.errors.comment) {
                        // Afficher les erreurs dans le conteneur des erreurs
                        result.errors.comment.forEach(error => {
                            const errorMsg = document.createElement('p');
                            errorMsg.textContent = error;
                            errorMsg.style.color = 'red';
                            commentErrorsDiv.appendChild(errorMsg);
                        });
                    } else if (result.error) {
                        alert(result.error);
                    }
                } else if (response.status === 401) {
                    alert('Vous devez être connecté pour commenter.');
                } else {
                    alert('Erreur lors de l\'ajout du commentaire.');
                }
            } catch (error) {
                console.error('Erreur réseau :', error); // Ajouter un log pour voir l'erreur en détail
                alert('Erreur réseau. Veuillez vérifier votre connexion.');
            }
        });
    });
});
</script>