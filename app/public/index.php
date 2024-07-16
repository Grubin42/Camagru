<?php
require_once __DIR__ . '/../main.php';

try {
    $db = getDBConnection();
    $stmt = $db->query('SELECT username, email FROM users');
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $users = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
</head>
<body>
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
</body>
</html>