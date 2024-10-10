document.querySelectorAll('.like-button').forEach(button => {
    button.addEventListener('click', function() {
        const form = this.closest('form');
        const postId = form.getAttribute('data-post-id');
        const likeCountElement = form.querySelector('.like-count');
        const csrfTokenInput = form.querySelector('input[name="csrf_token"]');
        const csrfToken = csrfTokenInput.value;

        fetch('/post/like', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}&csrf_token=${encodeURIComponent(csrfToken)}`
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
        const errorMessage = form.querySelector('.error-message');
        const commentText = commentInput.value.trim();
        const csrfTokenInput = form.querySelector('input[name="csrf_token"]');
        const csrfToken = csrfTokenInput.value;
        // Vérifier si le conteneur d'erreur existe
        if (!errorMessage) {
            return;
        }

        // Envoi de la requête POST via Fetch API
        fetch('/post/add_comment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `post_id=${postId}&comment=${encodeURIComponent(commentText)}&csrf_token=${encodeURIComponent(csrfToken)}`
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
                errorMessage.style.display = 'none'; // Cacher le message d'erreur en cas de succès
            } else {
                // Afficher un message d'erreur si la requête échoue
                errorMessage.textContent = data.errors ? data.errors.join(', ') : (data.message || 'Une erreur est survenue.');
                errorMessage.style.display = 'block';
            }
        })
        .catch(() => {
            errorMessage.textContent = 'Erreur de connexion ou de réponse. Veuillez réessayer.';
            errorMessage.style.display = 'block';
        });
    });
});