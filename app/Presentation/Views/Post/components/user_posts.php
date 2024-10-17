<!-- /Presentation/Views/Post/components/user_posts.php -->

<h2>Vos Posts</h2>
<?php if (isset($userPosts) && count($userPosts) > 0): ?>
    <?php foreach ($userPosts as $post): ?>
        <div class="user-post">
            <!-- Bouton de suppression -->
            <form action="/delete-post" method="POST" class="delete-post-form">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                <button type="submit" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">&times;</button>
            </form>
            <!-- Image du post -->
            <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image">
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Vous n'avez pas encore de posts.</p>
<?php endif; ?>