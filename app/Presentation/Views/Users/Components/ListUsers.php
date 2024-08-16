<main>
<?php if ($user): ?>
    <p><?php echo htmlspecialchars($user['username']); ?> - <?php echo htmlspecialchars($user['email']); ?></p>
<?php else: ?>
    <p>Aucun utilisateur trouvé.</p>
<?php endif; ?>
</main>