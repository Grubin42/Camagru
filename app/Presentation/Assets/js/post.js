document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('video');
    const finalCanvas = document.getElementById('final-canvas');
    const captureBtn = document.getElementById('capture-btn');
    const finalContext = finalCanvas.getContext('2d');
    const thumbnailsContainer = document.getElementById('thumbnails-container');
    let capturedImages = [];
    let selectedImageData = null;
    let selectedStickers = []; // Tableau pour stocker plusieurs stickers

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
        console.log('Remplacement du bouton de capture par un bouton de sélection de fichier.');

        // Supprimer le bouton "Capturer une image" s'il est présent
        const captureBtn = document.getElementById('capture-btn');
        if (captureBtn) {
            const captureButtonContainer = captureBtn.parentElement;
            
            // Vérifier si le parent existe avant de supprimer le bouton et de manipuler l'attribut
            if (captureButtonContainer) {
                captureButtonContainer.removeAttribute('data-tooltip');
                captureBtn.remove(); // Supprime le bouton du DOM
            } else {
                console.warn('captureButtonContainer est null. Impossible de retirer l\'attribut data-tooltip.');
            }
        }

        const captureSection = document.querySelector('.video-section');
        captureSection.innerHTML = ''; // Vider la section vidéo pour y ajouter l'input de fichier

        // Créer le conteneur du bouton
        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container');
        buttonContainer.setAttribute('data-tooltip', 'Sélectionnez une image.');

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

        if (fileInput) {
            fileInput.disabled = false;
            fileInputLabel.classList.remove('disabled-button');
            
            // Vérifier si le parent de fileInputLabel existe avant de manipuler ses attributs
            if (fileInputLabel && fileInputLabel.parentElement) {
                fileInputLabel.parentElement.removeAttribute('data-tooltip');
            } else {
                console.warn('Le parent de fileInputLabel est null. Impossible de retirer l\'attribut data-tooltip.');
            }
        }

        // Mettre à jour l'état des boutons
        updateButtonsState();
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
                // Initialiser l'état des boutons
                updateButtonsState();
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

        // Désactiver le bouton de capture au début si nécessaire
        // Ici, le bouton est activé indépendamment de la sélection des stickers
        // Vous pouvez choisir de le désactiver au début et l'activer après certaines conditions si nécessaire
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
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Empêcher la sélection de l'image lors du clic sur le bouton de suppression
                // Supprimer l'image du tableau et mettre à jour les miniatures
                capturedImages.splice(index, 1);
                if (selectedImageData === imageData) {
                    selectedImageData = null;
                    document.getElementById('captured-image').value = '';
                    updateFinalCanvas();
                }
                updateThumbnails();
                // Mettre à jour l'état des boutons si nécessaire
                if (capturedImages.length < 4) {
                    // Si moins de 4 images, permettre d'ajouter de nouvelles images
                }
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
            const stickerSrc = sticker.src;

            if (selectedStickers.includes(stickerSrc)) {
                // Si le sticker est déjà sélectionné, le désélectionner
                selectedStickers = selectedStickers.filter(src => src !== stickerSrc);
                sticker.classList.remove('selected-sticker');
            } else {
                // Ajouter le sticker à la sélection
                selectedStickers.push(stickerSrc);
                sticker.classList.add('selected-sticker');
            }

            // Mettre à jour les valeurs des champs cachés si nécessaire
            // Par exemple, pour stocker les stickers sélectionnés en tant que chaîne JSON
            document.getElementById('selected-stickers').value = JSON.stringify(selectedStickers);

            updateFinalCanvas();
            updateButtonsState();
        });
    });

    // Fonction pour mettre à jour l'état des boutons
    function updateButtonsState() {
        const captureBtn = document.getElementById('capture-btn');
        const fileInput = document.getElementById('file-input');
        const fileInputLabel = document.querySelector('label[for="file-input"]');

        // Ici, les boutons sont activés indépendamment de la sélection des stickers
        // Vous pouvez ajouter d'autres conditions si nécessaire

        // Exemple: Activer le bouton de capture si la caméra est disponible ou si l'input file est disponible
        if (captureBtn) {
            // Ici, vous pouvez ajouter des conditions spécifiques pour activer/désactiver le bouton
            // Par exemple, vérifier si la caméra est active
            // Dans cet exemple, nous le gardons toujours activé
            captureBtn.disabled = false;
            captureBtn.classList.remove('disabled-button');
        }

        if (fileInput && fileInputLabel) {
            // Activer ou désactiver le bouton de sélection de fichier en fonction des conditions
            // Ici, nous le gardons activé si l'input n'est pas déjà remplacé
            if (!fileInput.disabled) {
                fileInputLabel.classList.remove('disabled-button');
            } else {
                fileInputLabel.classList.add('disabled-button');
            }
        }
    }

    // Mettre à jour l'aperçu du canvas final
    function updateFinalCanvas() {
        finalContext.clearRect(0, 0, finalCanvas.width, finalCanvas.height);

        if (selectedImageData) {
            const image = new Image();
            image.src = selectedImageData;
            image.onload = () => {
                finalContext.drawImage(image, 0, 0, finalCanvas.width, finalCanvas.height);
                if (selectedStickers.length > 0) {
                    selectedStickers.forEach((stickerSrc, index) => {
                        const stickerImage = new Image();
                        stickerImage.src = stickerSrc;
                        stickerImage.onload = () => {
                            const stickerWidth = 100;
                            const stickerHeight = 100;
                            // Positionner chaque sticker avec un décalage
                            const xPosition = 10 + (index * 10);
                            const yPosition = 10 + (index * 10);

                            finalContext.drawImage(stickerImage, xPosition, yPosition, stickerWidth, stickerHeight);
                        };
                    });
                }
            };
        } else {
            // Si aucune image capturée, afficher uniquement les stickers sélectionnés
            if (selectedStickers.length > 0) {
                selectedStickers.forEach((stickerSrc, index) => {
                    const stickerImage = new Image();
                    stickerImage.src = stickerSrc;
                    stickerImage.onload = () => {
                        const stickerWidth = 100;
                        const stickerHeight = 100;
                        // Positionner chaque sticker avec un décalage
                        const xPosition = 10 + (index * 10);
                        const yPosition = 10 + (index * 10);
                        finalContext.drawImage(stickerImage, xPosition, yPosition, stickerWidth, stickerHeight);
                    };
                });
            }
        }
    }

    // Sauvegarder l'image fusionnée
    document.getElementById('post-form').addEventListener('submit', (event) => {
        if (!selectedImageData) {
            alert('Veuillez sélectionner une image capturée pour soumettre un post.');
            event.preventDefault();
            return;
        }
    
        // Sérialiser les stickers sélectionnés en JSON
        document.getElementById('selected-stickers').value = JSON.stringify(selectedStickers);
    
        // Log pour vérifier que les valeurs sont bien capturées
        console.log('Captured Image:', document.getElementById('captured-image').value);
        console.log('Selected Stickers:', document.getElementById('selected-stickers').value);
    
        // Laisser le formulaire se soumettre normalement
    });

    // Ajouter un champ caché pour stocker les stickers sélectionnés
    // const postForm = document.getElementById('post-form');
    // if (postForm) {
    //     const selectedStickersInput = document.createElement('input');
    //     selectedStickersInput.type = 'hidden';
    //     selectedStickersInput.id = 'selected-stickers';
    //     selectedStickersInput.name = 'selected_stickers';
    //     selectedStickersInput.value = JSON.stringify(selectedStickers);
    //     postForm.appendChild(selectedStickersInput);
    // }

    // Initialiser l'état des boutons
    updateButtonsState();
});