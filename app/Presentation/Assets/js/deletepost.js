function confirmDeletion(element) {
    // Récupère l'ID de la photo à partir de l'attribut data-id
    const postId = element.getAttribute('data-id');

    // Demande de confirmation à l'utilisateur
    const userConfirmed = confirm("Êtes-vous sûr de vouloir supprimer cette photo ?");

    if (userConfirmed) {
        // Si l'utilisateur confirme, on envoie une requête AJAX (fetch)
        fetch('/post/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${postId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Supprime la carte de la photo du DOM si la suppression est réussie
                element.closest('.card').remove();
            } else {
                // Affiche un message d'erreur en cas de problème
                alert(data.message || 'Une erreur est survenue lors de la suppression de la photo.');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue. Veuillez réessayer.');
        });
    }
}