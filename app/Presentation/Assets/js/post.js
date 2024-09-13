// Initialiser les éléments HTML
const video = document.getElementById('video');
const canvas = document.getElementById('canvas');
const captureButton = document.getElementById('capture');
const resetButton = document.getElementById('reset');
const photoInput = document.getElementById('photo'); // Champ caché pour la photo capturée
const stickerInput = document.getElementById('stickerBase64'); // Champ caché pour le sticker en base64
const stickerFileInput = document.getElementById('stickerFile'); // Input pour le fichier sticker
let context = canvas.getContext('2d');
let photoCaptured = false; // Variable pour vérifier si la photo est capturée
let stickerImage = null; // Image du sticker

// Facteur de redimensionnement (50% dans cet exemple)
const stickerScaleFactor = 0.5;

// Activer la webcam
navigator.mediaDevices.getUserMedia({ video: true })
    .then(stream => {
        video.srcObject = stream;
    })
    .catch(err => {
        console.error("Erreur de webcam: ", err);
    });

// Fonction pour capturer la photo
captureButton.addEventListener('click', () => {
    context.drawImage(video, 0, 0, canvas.width, canvas.height); // Capture l'image de la vidéo
    const dataURL = canvas.toDataURL('image/png'); // Convertir l'image en base64
    photoInput.value = dataURL; // Mettre la photo capturée dans un champ caché pour le formulaire
    photoCaptured = true; // Indiquer que la photo est capturée

    // Si un sticker a été chargé, l'ajouter visuellement sur la photo
    if (stickerImage) {
        const scaledWidth = stickerImage.width * stickerScaleFactor;
        const scaledHeight = stickerImage.height * stickerScaleFactor;

        // Position du sticker (en bas à droite avec 10px de marge)
        const x = canvas.width - scaledWidth - 10;
        const y = canvas.height - scaledHeight - 10;

        // Dessiner le sticker redimensionné sur le canvas
        context.drawImage(stickerImage, x, y, scaledWidth, scaledHeight);
    }
});

// Réinitialiser la capture
resetButton.addEventListener('click', () => {
    context.clearRect(0, 0, canvas.width, canvas.height); // Effacer le canvas
    photoCaptured = false; // Réinitialiser l'état de capture de la photo
});

// Gérer le téléchargement d'un sticker
stickerFileInput.addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            stickerImage = new Image();
            stickerImage.src = e.target.result; // Charger le fichier image comme sticker

            stickerImage.onload = function() {
                // Stocker l'image du sticker en base64 dans un champ caché pour l'envoyer au serveur
                stickerInput.value = stickerImage.src.replace(/^data:image\/(png|jpg|jpeg|gif);base64,/, '');

                // Si la photo est déjà capturée, ajouter visuellement le sticker
                if (photoCaptured) {
                    const scaledWidth = stickerImage.width * stickerScaleFactor;
                    const scaledHeight = stickerImage.height * stickerScaleFactor;

                    const x = canvas.width - scaledWidth - 10; // 10px de marge à droite
                    const y = canvas.height - scaledHeight - 10; // 10px de marge en bas

                    // Dessiner le sticker redimensionné sur la photo
                    context.drawImage(stickerImage, x, y, scaledWidth, scaledHeight);
                }
            };
        };
        reader.readAsDataURL(file); // Lire le fichier en base64
    } else {
        alert("Veuillez capturer une photo avant d'ajouter un sticker !");
    }
});

// Soumettre le formulaire lorsque les données sont prêtes
document.getElementById('photoForm').addEventListener('submit', function(event) {
    // Si le sticker ou la photo sont vides, empêcher la soumission
    if (document.getElementById('stickerBase64').value === "" || document.getElementById('photo').value === "") {
        event.preventDefault(); // Empêche la soumission si les données sont manquantes
        alert("Veuillez capturer une photo et choisir un sticker avant de soumettre le formulaire.");
    }
});