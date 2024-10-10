// Initialiser les éléments HTML
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const resetButton = document.getElementById('reset');
const uploadPhotoButton = document.getElementById('uploadPhotoButton');
const fileInput = document.getElementById('fileInput');
const photoInput = document.getElementById('photo'); // Champ caché pour la photo capturée
const stickerBase64Input = document.getElementById('stickerBase64'); // Champ caché pour le sticker en base64
let context = canvas.getContext('2d');
let photoCaptured = false; // Variable pour vérifier si la photo est capturée
let stickerImage = null; // Image du sticker

// Activer la webcam ou afficher le bouton d'upload si refus ou absence de webcam
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(err => {
        // Afficher le bouton pour uploader une photo si pas de webcam ou refus d'accès
        uploadPhotoButton.style.display = "inline-block";
    });

// Fonction pour capturer la photo
captureButton.addEventListener('click', () => {
    // Capture l'image de la vidéo dans le canvas
    context.drawImage(video, 0, 0, canvas.width, canvas.height);
    const dataURL = canvas.toDataURL('image/png'); // Convertir l'image en base64
    photoInput.value = dataURL; // Mettre la photo capturée dans un champ caché
    photoCaptured = true; // Indiquer que la photo est capturée

    // Si un sticker a été chargé, l'ajouter visuellement sur la photo
    if (stickerImage) {
        const scaledWidth = stickerImage.width * 0.5;
        const scaledHeight = stickerImage.height * 0.5;

        // Positionner le sticker en bas à droite avec une marge de 10px
        const x = canvas.width - scaledWidth - 10;
        const y = canvas.height - scaledHeight - 10;

        // Dessiner le sticker redimensionné sur le canvas
        context.drawImage(stickerImage, x, y, scaledWidth, scaledHeight);
    }
});

// Fonction pour uploader une photo
uploadPhotoButton.addEventListener('click', () => {
    fileInput.click(); // Ouvrir la boîte de dialogue pour choisir une photo
});

// Gérer la sélection d'une image uploadée
fileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        const img = new Image();
        img.src = e.target.result;
        
        img.onload = function() {
            // Dessiner l'image uploadée dans le canvas
            context.drawImage(img, 0, 0, canvas.width, canvas.height);
            const dataURL = canvas.toDataURL('image/png'); // Convertir l'image en base64
            photoInput.value = dataURL; // Mettre la photo uploadée dans le champ caché
            photoCaptured = true; // Marquer la photo comme capturée
        };
    };
    reader.readAsDataURL(file);
});

// Réinitialiser la capture et la sélection du sticker
resetButton.addEventListener('click', () => {
    context.clearRect(0, 0, canvas.width, canvas.height); // Effacer le canvas
    photoCaptured = false; // Réinitialiser l'état de capture de la photo
    captureButton.disabled = true; // Désactiver le bouton "Prendre la photo"
    uploadPhotoButton.disabled = true; // Désactiver le bouton "Choisir une photo"
    stickerImage = null; // Réinitialiser le sticker sélectionné

    // Supprimer la sélection visuelle des stickers
    document.querySelectorAll('.sticker-preview').forEach(sticker => {
        sticker.classList.remove('sticker-selected');
    });
});

// Gérer la sélection des stickers
document.querySelectorAll('.sticker-preview').forEach(sticker => {
    sticker.addEventListener('click', function() {
        // Supprimer l'indicateur visuel de tous les stickers
        document.querySelectorAll('.sticker-preview').forEach(s => s.classList.remove('sticker-selected'));

        // Ajouter l'indicateur visuel au sticker sélectionné
        this.classList.add('sticker-selected');

        // Charger l'image du sticker sélectionné
        stickerImage = new Image();
        stickerImage.src = this.dataset.stickerSrc;

        // Activer les boutons "Prendre la photo" et "Choisir une photo" après la sélection du sticker
        captureButton.disabled = false;
        captureButton.style.cursor = "pointer"; // Changer l'apparence du curseur
        uploadPhotoButton.disabled = false;
        uploadPhotoButton.style.cursor = "pointer"; // Changer l'apparence du curseur

        // Mettre à jour le champ caché pour l'envoyer avec le formulaire
        const canvasForSticker = document.createElement('canvas');
        const ctx = canvasForSticker.getContext('2d');
        canvasForSticker.width = stickerImage.width;
        canvasForSticker.height = stickerImage.height;

        stickerImage.onload = function() {
            ctx.drawImage(stickerImage, 0, 0);
            const stickerBase64 = canvasForSticker.toDataURL('image/png');
            stickerBase64Input.value = stickerBase64;
        };
    });
});

// Gérer la soumission du formulaire
document.getElementById('photoForm').addEventListener('submit', function(event) {
    // Vérifier que la photo et le sticker sont bien présents avant de soumettre
    if (!photoCaptured || stickerBase64Input.value === "") {
        event.preventDefault(); // Empêcher la soumission si les données sont manquantes
        alert("Veuillez capturer une photo et choisir un sticker avant de soumettre.");
    }
});

function confirmDeletion(element) {
    // Récupère l'ID de la photo à partir de l'attribut data-id
    const postId = element.getAttribute('data-id');
    const csrfTokenInput = element.querySelector('input[name="csrf_token"]');
    const csrfToken = csrfTokenInput.value;
    // Demande de confirmation à l'utilisateur
    const userConfirmed = confirm("Êtes-vous sûr de vouloir supprimer cette photo ?");

    if (userConfirmed) {
        // Si l'utilisateur confirme, on envoie une requête AJAX (fetch)
        fetch('/post/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${postId}&csrf_token=${encodeURIComponent(csrfToken)}`
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