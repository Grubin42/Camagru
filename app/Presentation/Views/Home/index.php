<h1>Bienvenue sur Camagru</h1>
<p>Ceci est la page d'accueil de votre application. Utilisez le menu de navigation pour explorer les fonctionnalités.</p>

<?php if (!empty($posts)): ?>
    <h2>Les 5 derniers posts</h2>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image">
                <div>
                    <p>Date: <?= $post['created_date'] ?></p>
                    <p>id: <?= $post['id'] ?> </p>
                    <button>like</button>
                    <button onclick="toggleComments(<?= $post['id'] ?>)">view comments</button>
                    <button onclick="toggleCommentInput(<?= $post['id'] ?>)">comment</button>
                </div>
                <!-- Comment input form -->
                <div id="comment-section-<?= $post['id'] ?>" class="comment-section" style="display: none;">
                    <form action="/comment" method="POST">
                        <input type="hidden" name="postId" value="<?= $post['id'] ?>">
                        <input type="hidden" name="username" value="testusername"> <!-- Assuming you have the current user object -->
                        <textarea name="comment" placeholder="Type your comment here..." required></textarea>
                        <button type="submit">Submit Comment</button>
                    </form>
                </div>

                <!-- Comments display section -->
                <div id="comments-<?= $post['id'] ?>" class="comments" style="display:none;">
                    <h4>Comments:</h4>
                    <?php if (!empty($post['comments'])): ?>
                        <ul>
                            <?php foreach ($post['comments'] as $comment): ?>
                                <li>
                                    <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
                                    <p><?= htmlspecialchars($comment['commentaire']) ?></p>
                                    <em><?= htmlspecialchars($comment['created_date']) ?></em>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No comments available.</p>
                    <?php endif; ?>
                </div>

            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun post disponible.</p>
<?php endif; ?>



<script>

function toggleComments(postId) {
    const commentsSection = document.getElementById('comments-' + postId);
    if (commentsSection.style.display === 'none' || commentsSection.style.display === '') {
        commentsSection.style.display = 'block';
    } else {
        commentsSection.style.display = 'none';
    }}

    function toggleCommentInput(postId) {
        var commentSection = document.getElementById('comment-section-' + postId);
        if (commentSection.style.display === 'none' || commentSection.style.display === '') {
            commentSection.style.display = 'block';
        } else {
            commentSection.style.display = 'none';
        }
    }

    function submitComment(postId) {
    var commentInput = document.getElementById('comment-input-' + postId).value;
    
    if (commentInput.trim() === '') {
        alert("Please enter a comment before submitting.");
        return;
    }

    // Here you can implement the AJAX call to submit the comment to the server
    // Or handle the comment input submission as per your app architecture
    console.log("Comment submitted for Post ID " + postId + ": " + commentInput);
    
    // Clear the input after submission
    document.getElementById('comment-input-' + postId).value = '';
    toggleCommentInput(postId); // Optionally hide input after submitting
}
</script>