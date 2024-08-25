document.querySelector('form').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche la soumission normale du formulaire

    // Récupérez les valeurs des champs
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Envoie des données au contrôleur via AJAX
    fetch('/user/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ username, password }),
    })
    .then(response => response.json())
    .then(data => {
        // Traitez la réponse du contrôleur ici (par exemple, redirigez l'utilisateur)
        console.log(data);
    })
    .catch(error => {
        console.error('Erreur lors de l\'envoi des données au contrôleur :', error);
    });
});