<main>
    <h1>Liste des utilisateurs</h1>
    <ul>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <li><?php echo htmlspecialchars($user['username']); ?> (<?php echo htmlspecialchars($user['email']); ?>)</li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Aucun utilisateur trouvé.</li>
        <?php endif; ?>
    </ul>
</main>