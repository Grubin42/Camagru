<div class="posts-container">
    <h2>Créer un nouveau post</h2>

    <!-- Section pour capturer une image avec la caméra -->
    <div class="capture-section">
        <h3>Capture d'image</h3>
        <video id="video" width="320" height="240" autoplay></video>
        <button id="capture-btn">Capturer</button>
    </div>

    <!-- Section pour afficher l'image capturée -->
    <div id="captured-image-section" style="display: none;">
        <h3>Image capturée</h3>
        <canvas id="canvas" width="320" height="240"></canvas>
        <button id="delete-image-btn">Supprimer l'image</button>
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

    <!-- Section pour voir le résultat final -->
    <div class="result-section">
        <h3>Résultat final</h3>
        <canvas id="final-canvas" width="320" height="240"></canvas>
    </div>

    <!-- Bouton pour soumettre le post -->
    <form action="/posts/save" method="POST" id="post-form">
        <input type="hidden" id="captured-image" name="captured_image">
        <input type="hidden" id="selected-sticker" name="selected_sticker">
        <button type="submit">Soumettre</button>
    </form>
</div>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const finalCanvas = document.getElementById('final-canvas');
    const captureBtn = document.getElementById('capture-btn');
    const deleteImageBtn = document.getElementById('delete-image-btn');
    const capturedImageSection = document.getElementById('captured-image-section');
    const context = canvas.getContext('2d');
    const finalContext = finalCanvas.getContext('2d');
    let capturedImageData = null;
    let selectedSticker = null;

    // Demander l'accès à la caméra
    navigator.mediaDevices.getUserMedia({ video: true })
        .then(stream => {
            video.srcObject = stream;
        });

    // Capturer l'image lorsqu'on appuie sur le bouton "Capturer"
    captureBtn.addEventListener('click', () => {
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        capturedImageData = canvas.toDataURL();
        capturedImageSection.style.display = 'block';
        document.getElementById('captured-image').value = capturedImageData;
        updateFinalCanvas();
    });

    // Supprimer l'image capturée et réinitialiser le canvas
    deleteImageBtn.addEventListener('click', () => {
        context.clearRect(0, 0, canvas.width, canvas.height);
        finalContext.clearRect(0, 0, finalCanvas.width, finalCanvas.height);
        capturedImageData = null;
        document.getElementById('captured-image').value = '';
        document.getElementById('final-image').value = '';
        capturedImageSection.style.display = 'none';
    });

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
        if (capturedImageData) {
            const image = new Image();
            image.src = capturedImageData;
            image.onload = () => {
                finalContext.clearRect(0, 0, finalCanvas.width, finalCanvas.height);
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
    document.getElementById('final-image').value = finalCanvas.toDataURL(); // Sauvegarder l'image fusionnée
    console.log('Captured Image:', document.getElementById('captured-image').value);
    console.log('Selected Sticker:', document.getElementById('selected-sticker').value);
    console.log('Final Image:', document.getElementById('final-image').value);
});
</script>