<?php if ($user): ?>
    <h2>Dernier utilisateur</h2>
    <p><strong>Nom d'utilisateur:</strong> <?= htmlspecialchars($user['username']) ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>
