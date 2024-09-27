<h1>Mon Profil</h1>
<p>Nom d'utilisateur : <?= htmlspecialchars($user['username']) ?></p>
<p>Email : <?= htmlspecialchars($user['email']) ?></p>
<p>Notifications : <?= $user['notif'] ? 'Activées' : 'Désactivées' ?></p>

<a href="/edit-profile">Modifier mon profil</a>