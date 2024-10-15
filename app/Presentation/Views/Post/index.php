<div class="main-container">
    <!-- Colonne de gauche -->
    <div class="left-column">
        <!-- Section en haut à gauche : Caméra et stickers -->
        <div class="top-left">
            <h2>Créer un nouveau post</h2>
            <!-- Section pour capturer une image avec la caméra -->
            <div class="capture-section">
                <h3>Capture d'image</h3>
                <div class="capture-container">
                    <!-- Vidéo et bouton de capture -->
                    <div class="video-section">
                        <video id="video" width="320" height="240" autoplay></video>
                        <button id="capture-btn">Capturer</button>
                    </div>
                    <!-- Miniatures des images capturées -->
                    <div class="thumbnails-section">
                        <h4>Images capturées</h4>
                        <div id="thumbnails-container">
                            <!-- Les miniatures seront ajoutées ici dynamiquement -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section pour sélectionner une image à ajouter -->
            <div class="sticker-selection">
                <h3>Sélectionner un sticker à ajouter</h3>
                <ul id="sticker-list">
                    <?php
                    // Chemin vers le répertoire contenant les stickers
                    $stickerDir = __DIR__ . '/../../Assets/images/'; 
                    $stickers = glob($stickerDir . '*.{jpg,png,gif}', GLOB_BRACE);
                    foreach ($stickers as $stickerPath) {
                        $stickerName = basename($stickerPath);
                        $stickerUrl = '/Presentation/Assets/images/' . $stickerName;
                        echo '<li><img src="' . $stickerUrl . '" alt="' . $stickerName . '" class="sticker"></li>';
                    }
                    ?>
                </ul>
            </div>
        </div>

        <!-- Section en bas à gauche : Résultat final et bouton soumettre -->
        <div class="bottom-left">
            <!-- Section pour voir le résultat final -->
            <div class="result-section">
                <h3>Résultat final</h3>
                <canvas id="final-canvas" width="320" height="240"></canvas>
            </div>

            <!-- Bouton pour soumettre le post -->
            <form action="/posts" method="POST" id="post-form">
                <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" id="captured-image" name="captured_image">
                <input type="hidden" id="selected-sticker" name="selected_sticker">
                <button type="submit">Soumettre</button>
            </form>
        </div>
    </div>

    <!-- Colonne de droite : Affichage des posts de l'utilisateur -->
    <div class="right-column">
        <h2>Vos Posts</h2>
        <?php if (isset($userPosts) && count($userPosts) > 0): ?>
            <?php foreach ($userPosts as $post): ?>
                <div class="user-post">
                    <!-- Bouton de suppression -->
                    <form action="/delete-post" method="POST" class="delete-post-form">
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <button type="submit" class="delete-post-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">&times;</button>
                    </form>
                    <!-- Image du post -->
                    <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n'avez pas encore de posts.</p>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const finalCanvas = document.getElementById('final-canvas');
    const captureBtn = document.getElementById('capture-btn');
    const finalContext = finalCanvas.getContext('2d');
    const thumbnailsContainer = document.getElementById('thumbnails-container');
    let capturedImages = []; // Tableau pour stocker les images capturées
    let selectedImageData = null;
    let selectedSticker = null;

    // Demander l'accès à la caméra
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        });

    // Capturer l'image lorsqu'on appuie sur le bouton "Capturer"
    captureBtn.addEventListener('click', () => {
        if (capturedImages.length >= 4) {
            alert('Vous avez déjà 4 images capturées. Veuillez supprimer une image pour en capturer une nouvelle.');
            return;
        }

        const canvas = document.createElement('canvas');
        canvas.width = 320;
        canvas.height = 240;
        const context = canvas.getContext('2d');

        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL();

        // Ajouter l'image au tableau des images capturées
        capturedImages.push(imageData);
        updateThumbnails();
    });

    // Mettre à jour l'affichage des miniatures
    function updateThumbnails() {
        // Vider le conteneur des miniatures
        thumbnailsContainer.innerHTML = '';

        capturedImages.forEach((imageData, index) => {
            const thumbnailDiv = document.createElement('div');
            thumbnailDiv.classList.add('thumbnail');

            const img = document.createElement('img');
            img.src = imageData;
            img.alt = 'Captured Image ' + (index + 1);
            img.addEventListener('click', () => {
                // Sélectionner l'image pour l'afficher dans le résultat final
                selectedImageData = imageData;
                document.getElementById('captured-image').value = selectedImageData;
                updateFinalCanvas();
            });

            const deleteBtn = document.createElement('button');
            deleteBtn.classList.add('delete-thumbnail-button');
            deleteBtn.innerHTML = '&times;';
            deleteBtn.addEventListener('click', () => {
                // Supprimer l'image du tableau et mettre à jour les miniatures
                capturedImages.splice(index, 1);
                if (selectedImageData === imageData) {
                    selectedImageData = null;
                    document.getElementById('captured-image').value = '';
                    updateFinalCanvas();
                }
                updateThumbnails();
            });

            thumbnailDiv.appendChild(img);
            thumbnailDiv.appendChild(deleteBtn);
            thumbnailsContainer.appendChild(thumbnailDiv);
        });
    }

    // Gestion des stickers
    const stickers = document.querySelectorAll('.sticker');
    stickers.forEach(sticker => {
        sticker.addEventListener('click', () => {
            selectedSticker = sticker.src;
            document.getElementById('selected-sticker').value = selectedSticker; // Mettre à jour le champ caché
            updateFinalCanvas();
        });
    });

    // Mettre à jour l'aperçu du canvas final
    function updateFinalCanvas() {
        finalContext.clearRect(0, 0, finalCanvas.width, finalCanvas.height);

        if (selectedImageData) {
            const image = new Image();
            image.src = selectedImageData;
            image.onload = () => {
                finalContext.drawImage(image, 0, 0, finalCanvas.width, finalCanvas.height);
                if (selectedSticker) {
                    const stickerImage = new Image();
                    stickerImage.src = selectedSticker;
                    stickerImage.onload = () => {
                        finalContext.drawImage(stickerImage, 0, 0, finalCanvas.width, finalCanvas.height);
                    };
                }
            };
        }
    }

    // Sauvegarder l'image fusionnée
    document.getElementById('post-form').addEventListener('submit', (event) => {
        if (!selectedImageData) {
            alert('Veuillez sélectionner une image capturée pour soumettre un post.');
            event.preventDefault();
            return;
        }

        // Log pour vérifier que les valeurs sont bien capturées
        console.log('Captured Image:', document.getElementById('captured-image').value);
        console.log('Selected Sticker:', document.getElementById('selected-sticker').value);

        // Laisser le formulaire se soumettre normalement
    });
});
</script>