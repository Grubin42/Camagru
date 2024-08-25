<h1>Bienvenue sur Camagru</h1>
<p>Ceci est la page d'accueil de votre application. Utilisez le menu de navigation pour explorer les fonctionnalités.</p>

<?php if (!empty($posts)): ?>
    <h2>Les 5 derniers posts</h2>
    <ul>
        <?php foreach ($posts as $post): ?>
            <li>
                <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image">
                <p>Date: <?= $post['created_date'] ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>Aucun post disponible.</p>
<?php endif; ?>