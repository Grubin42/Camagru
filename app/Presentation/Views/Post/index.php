<style>
    #video { width: 50%; }
    #canvas { display: none; }
    .img-preview { max-width: 50%; }
</style>
<div class="container text-center mt-4">
        <div class="row">
            <div class="col">
                <video id="video" class="mb-2" autoplay></video>
                <button id="capture" class="btn btn-primary mb-2">Capturer</button>
                <canvas id="canvas"></canvas>
                <img id="photo" class="img-preview img-fluid mb-2" src="" alt="Preview">
                <input type="file" id="sticker" class="form-control-file mb-2" accept="image/*">
                <button id="add-sticker" class="btn btn-secondary mb-2">Ajouter Sticker</button>
                <button id="reset" class="btn btn-warning mb-2">Réinitialiser</button>
                <button id="save" class="btn btn-success">Sauvegarder</button>
                
                <!-- Formulaire pour envoyer les images -->
                <form id="image-form" action="/post/save" method="POST" enctype="multipart/form-data" style="display: none;">
                    <input type="hidden" id="image-data" name="image">
                    <input type="hidden" id="sticker-data" name="sticker">
                </form>
            </div>
        </div>
    </div>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const photo = document.getElementById('photo');
        const stickerInput = document.getElementById('sticker');
        const addStickerButton = document.getElementById('add-sticker');
        const resetButton = document.getElementById('reset');
        const saveButton = document.getElementById('save');
        const form = document.getElementById('image-form');
        const imageDataInput = document.getElementById('image-data');
        const stickerDataInput = document.getElementById('sticker-data');
        let stickerImage = null;

        // Accéder à la caméra
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(err => console.error('Erreur d’accès à la caméra :', err));

        // Capturer l'image
        document.getElementById('capture').addEventListener('click', () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            photo.src = canvas.toDataURL('image/png');
        });

        // Ajouter un sticker
        addStickerButton.addEventListener('click', () => {
            if (stickerImage) {
                const stickerX = 0; // Position du sticker en x
                const stickerY = 0; // Position du sticker en y
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                context.drawImage(stickerImage, stickerX, stickerY);
                photo.src = canvas.toDataURL('image/png');
            }
        });

        stickerInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = () => {
                    stickerImage = new Image();
                    stickerImage.src = reader.result;
                };
                reader.readAsDataURL(file);
            }
        });

        // Réinitialiser
        resetButton.addEventListener('click', () => {
            stickerImage = null;
            photo.src = '';
            stickerInput.value = '';
        });

        // Sauvegarder l'image en base64 et envoyer le formulaire
        saveButton.addEventListener('click', () => {
            const base64Image = canvas.toDataURL('image/png');
            imageDataInput.value = base64Image;
            stickerDataInput.value = stickerImage ? stickerImage.src : '';
            form.submit();
        });
    </script>