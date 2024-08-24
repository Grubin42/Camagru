<h1>Feed</h1>

<!-- <?php if (!empty($posts)): ?>
    <h2>Posts</h2>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <h3>Post by <?= htmlspecialchars($post['username']) ?> at <?= htmlspecialchars($post['created_at']) ?></h3>
            <p><strong>Image:</strong> <img src="data:image/png;base64,<?= base64_encode($post['image']) ?>" alt="Post Image"></p>
            <h4>Comments:</h4>
            <?php if (!empty($post['comments'])): ?>
                <ul>
                    <?php foreach ($post['comments'] as $comment): ?>
                        <li>
                            <p><strong><?= htmlspecialchars($comment['username']) ?>:</strong> <?= htmlspecialchars($comment['content']) ?></p>
                            <p><small>Posted at: <?= htmlspecialchars($comment['created_at']) ?></small></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>No comments yet.</p>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <p>No posts found.</p>
<?php endif; ?> -->