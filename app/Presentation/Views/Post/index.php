<div class="main-container">

    <?php
    if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])):
    ?>
        <div class="error-messages">
            <?php foreach ($_SESSION['errors'] as $field => $errors): ?>
                <?php foreach ($errors as $error): ?>
                    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    <?php
        // Réinitialiser les erreurs après les avoir affichées
        unset($_SESSION['errors']);
    endif;
    ?>
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
                        <div class="button-container" data-tooltip="Veuillez sélectionner un sticker avant de capturer une image.">
                            <button id="capture-btn" class="action-button">Capturer</button>
                        </div>
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
                    $stickers = glob($stickerDir . '*.{jpg,png,gif,jpeg}', GLOB_BRACE);
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
                <button type="submit" class="action-button">Soumettre</button>
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
                        <button type="submit" class="delete-button" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce post ?');">&times;</button>
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

<!-- Script JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const finalCanvas = document.getElementById('final-canvas');
    const captureBtn = document.getElementById('capture-btn');
    const finalContext = finalCanvas.getContext('2d');
    const thumbnailsContainer = document.getElementById('thumbnails-container');
    let capturedImages = [];
    let selectedImageData = null;
    let selectedSticker = null;

    // Fonction pour redimensionner une image tout en maintenant le ratio d'aspect
    function resizeImage(dataURL, maxWidth, maxHeight, callback) {
        const img = new Image();
        img.onload = function() {
            let width = img.width;
            let height = img.height;

            // Calculer le ratio de redimensionnement
            const widthRatio = maxWidth / width;
            const heightRatio = maxHeight / height;
            const ratio = Math.min(widthRatio, heightRatio, 1); // Ne pas agrandir si l'image est plus petite

            width = width * ratio;
            height = height * ratio;

            // Créer un canvas pour dessiner l'image redimensionnée
            const canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(img, 0, 0, width, height);

            // Obtenir le Data URL de l'image redimensionnée
            const resizedDataURL = canvas.toDataURL('image/png');
            callback(resizedDataURL);
        };
        img.src = dataURL;
    }

    // Fonction pour remplacer le bouton de capture par un input file stylisé
    function replaceCaptureButtonWithFileInput() {
        const captureSection = document.querySelector('.video-section');
        captureSection.innerHTML = '';

        // Créer le conteneur du bouton
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container');
        buttonContainer.setAttribute('data-tooltip', 'Veuillez sélectionner un sticker avant de sélectionner une image.');

        // Créer le label stylisé comme un bouton
        const fileInputLabel = document.createElement('label');
        fileInputLabel.setAttribute('for', 'file-input');
        fileInputLabel.textContent = 'Sélectionner une image';
        fileInputLabel.classList.add('action-button', 'disabled-button');
        fileInputLabel.style.display = 'inline-block';

        // Créer l'input de fichier caché
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.id = 'file-input';
        fileInput.accept = 'image/png, image/jpeg, image/jpg, image/gif';
        fileInput.style.display = 'none';
        fileInput.disabled = true; // Désactiver l'input au début

        // Ajouter un message d'instruction
        const instruction = document.createElement('p');
        instruction.id = 'capture-instruction';
        instruction.textContent = 'Veuillez sélectionner une image (PNG, JPG, JPEG, GIF) de moins de 5 Mo.';
        instruction.style.textAlign = 'center';
        instruction.style.color = '#555';
        instruction.style.fontSize = '14px';

        // Ajouter le label au conteneur
        buttonContainer.appendChild(fileInputLabel);

        // Ajouter le conteneur, l'input et l'instruction à la section
        captureSection.appendChild(buttonContainer);
        captureSection.appendChild(fileInput);
        captureSection.appendChild(instruction);

        // Gérer la sélection de fichier
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                // Vérifier la taille du fichier (<=5MB)
                if (file.size > 5 * 1024 * 1024) {
                    alert('La taille du fichier doit être inférieure ou égale à 5 Mo.');
                    return;
                }

                // Vérifier le type de fichier
                const validTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Type de fichier invalide. Veuillez sélectionner un fichier PNG, JPG, JPEG ou GIF.');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const originalDataURL = e.target.result;

                    // Redimensionner l'image avant de l'ajouter
                    resizeImage(originalDataURL, 320, 240, function(resizedDataURL) {
                        if (capturedImages.length >= 4) {
                            alert('Vous avez déjà 4 images capturées. Veuillez supprimer une image pour en ajouter une nouvelle.');
                            return;
                        }

                        // Ajouter l'image redimensionnée au tableau des images capturées
                        capturedImages.push(resizedDataURL);
                        updateThumbnails();
                    });
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Vérifier si le contexte est sécurisé
    function isSecureContext() {
        return window.isSecureContext || location.protocol === 'https:';
    }

    // Vérifier si getUserMedia est supporté et autorisé
    if (isSecureContext() && navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        // Demander l'accès à la caméra
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(err => {
                // L'utilisateur a refusé l'accès ou la caméra n'est pas disponible
                console.warn("Accès à la caméra refusé ou non disponible. Passage au mode de sélection de fichier.");
                // Remplacer le bouton de capture par l'input file stylisé
                replaceCaptureButtonWithFileInput();
            });
    } else {
        // getUserMedia non supporté ou contexte non sécurisé
        console.warn("getUserMedia n'est pas supporté par ce navigateur ou le contexte n'est pas sécurisé. Passage au mode de sélection de fichier.");
        // Remplacer le bouton de capture par l'input file stylisé
        replaceCaptureButtonWithFileInput();
    }

    // Capturer l'image lorsqu'on appuie sur le bouton "Capturer"
    if (captureBtn) {
        captureBtn.addEventListener('click', () => {
            if (!selectedSticker) {
                alert('Veuillez sélectionner un sticker avant de capturer une image.');
                return;
            }

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

        // Désactiver le bouton de capture au début
        captureBtn.disabled = true;
        captureBtn.classList.add('disabled-button');
    }

    // Fonction pour mettre à jour les miniatures
    function updateThumbnails() {
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
            deleteBtn.classList.add('delete-button', 'delete-thumbnail');
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
            // Retirer la sélection précédente
            stickers.forEach(s => s.classList.remove('selected-sticker'));

            // Sélectionner le nouveau sticker
            selectedSticker = sticker.src;
            sticker.classList.add('selected-sticker');
            document.getElementById('selected-sticker').value = selectedSticker;

            updateFinalCanvas();

            // Activer le bouton de capture ou le file input
            if (captureBtn) {
                captureBtn.disabled = false;
                captureBtn.classList.remove('disabled-button');
                // Retirer l'infobulle du conteneur
                const captureButtonContainer = captureBtn.parentElement;
                captureButtonContainer.removeAttribute('data-tooltip');
            }

            const fileInput = document.getElementById('file-input');
            const fileInputLabel = document.querySelector('label[for="file-input"]');

            if (fileInput) {
                fileInput.disabled = false;
                fileInputLabel.classList.remove('disabled-button');
                // Retirer l'infobulle du conteneur
                fileInputLabel.parentElement.removeAttribute('data-tooltip');
            }
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
                        const stickerWidth = 100;
                        const stickerHeight = 100;
                        const xPosition = 10;
                        const yPosition = 10;

                        finalContext.drawImage(stickerImage, xPosition, yPosition, stickerWidth, stickerHeight);
                    };
                }
            };
        } else if (selectedSticker) {
            const stickerImage = new Image();
            stickerImage.src = selectedSticker;
            stickerImage.onload = () => {
                const stickerWidth = 100;
                const stickerHeight = 100;
                const xPosition = 10;
                const yPosition = 10;
                finalContext.drawImage(stickerImage, xPosition, yPosition, stickerWidth, stickerHeight);
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