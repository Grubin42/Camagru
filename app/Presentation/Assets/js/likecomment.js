document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('form');
        const postId = form.getAttribute('data-post-id');
        const likeCountElement = form.querySelector('.like-count');

        fetch('/post/like', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mettre à jour l'interface utilisateur avec le nouveau nombre de likes
                likeCountElement.textContent = `${data.likes} likes`;
            } else {
                console.error(data.message);  // Gérer l'erreur
            }
        })
        .catch(error => console.error('Error:', error));
    });
});

document.querySelectorAll('.comment-form').forEach(form => {
    form.querySelector('button').addEventListener('click', function(event) {
        event.preventDefault();  // Empêcher la soumission par défaut du formulaire

        const postId = form.getAttribute('data-post-id');
        const commentInput = form.querySelector('input[name="comment"]');
        const commentList = document.getElementById(`comment-list-${postId}`);

        // Envoi de la requête POST via Fetch API
        fetch('/post/add_comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}&comment=${encodeURIComponent(commentInput.value)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Créer un nouvel élément <li> pour le commentaire
                const newComment = document.createElement('li');
                newComment.classList.add('list-group-item');
                newComment.innerHTML = `<strong>${data.username}:</strong> ${data.comment}`;
                
                // Ajouter le nouveau commentaire à la liste
                commentList.appendChild(newComment);

                // Réinitialiser le champ de texte du commentaire
                commentInput.value = '';
            } else {
                console.error(data.message);  // Afficher l'erreur si quelque chose s'est mal passé
            }
        })
        .catch(error => console.error('Erreur:', error));
    });
});