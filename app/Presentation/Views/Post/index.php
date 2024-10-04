
<div class="container mt-5">
    <div class="card">
        <div class="card-header text-center">
            <h1>Prendre une photo avec un sticker</h1>
        </div>

        <div class="card-body">
            <div class="row">
                <!-- Section pour prendre la photo et voir le résultat -->
                <div class="col-md-6">
                    <div id="cameraSection" class="d-flex justify-content-center">
                        <!-- Vidéo à gauche -->
                        <div>
                            <h5 class="text-center">Vidéo en direct</h5>
                            <video id="video" width="320" height="240" class="img-fluid border" autoplay></video>
                        </div>

                        <!-- Canvas (résultat) à droite -->
                        <div class="ms-3">
                            <h5 class="text-center">Résultat</h5>
                            <canvas id="canvas" width="320" height="240" class="img-fluid border"></canvas>
                        </div>
                    </div>

                    <!-- Boutons capture, réinitialiser et upload -->
                    <div class="d-flex justify-content-center mt-3">
                        <button id="capture" class="btn btn-primary me-2" disabled>Prendre la photo</button>
                        <button id="reset" class="btn btn-warning">Recommencer</button>
                        <button id="uploadPhotoButton" class="btn btn-secondary ms-2" disabled>Choisir une photo</button>
                    </div>

                    <!-- Input pour uploader une image si pas de webcam -->
                    <input type="file" id="fileInput" accept="image/*" style="display: none;" />

                    <!-- Section des stickers à choisir, sous les boutons -->
                    <div class="text-center mt-4">
                        <h5>Choisissez un sticker</h5>
                        <div id="stickerSelection" class="d-flex justify-content-center flex-wrap">
                            <!-- Stickers plus petits avec bordure par défaut et curseur changé -->
                            <img src="/Presentation/Assets/img/pinguin.gif" class="sticker-preview m-2" style="width: 50px; height: 50px;" data-sticker-src="/Presentation/Assets/img/pinguin.gif" />
                            <img src="/Presentation/Assets/img/internet.jpeg" class="sticker-preview m-2" style="width: 50px; height: 50px;" data-sticker-src="/Presentation/Assets/img/internet.jpeg" />
                            <img src="/Presentation/Assets/img/pinguin.gif" class="sticker-preview m-2" style="width: 50px; height: 50px;" data-sticker-src="/Presentation/Assets/img/pinguin.gif" />
                        </div>
                    </div>
                </div>

                <!-- Section pour les photos déjà capturées à droite -->
                <div class="col-md-6">
                    <h5 class="text-center">Mes Photos</h5>
                    <div style="max-height: 300px; overflow-y: auto;">
                        <div class="d-flex flex-column align-items-center">
                            <?php if (!empty($posts)): ?>
                                <?php foreach ($posts as $post): ?>
                                    <div class="card mb-2" style="max-width: 120px;">
                                        <div class="card-body p-2">
                                            <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image" class="img-fluid" style="width: 100px; height: 75px;" />
                                            <p class="small text-muted mt-1 text-center">Posté le : <?= date('d-m-Y', strtotime($post['created_date'])) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-center">Aucun post disponible.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire pour soumettre la photo capturée -->
        <div class="card-footer text-center">
            <form id="photoForm" method="post" action="/post/save">
                <input type="hidden" id="photo" name="photo">
                <input type="hidden" id="stickerBase64" name="sticker">
                <button type="submit" class="btn btn-success">Envoyer la photo</button>
            </form>
        </div>
    </div>
</div>
<style>
    .sticker-preview {
        cursor: pointer;
        border: 2px solid transparent;
        transition: border-color 0.3s ease;
    }

    .sticker-selected {
        border-color: blue;
    }

    /* Boutons désactivés par défaut */
    #uploadPhotoButton,
    #capture {
        cursor: not-allowed;
    }

    /* Masquer le bouton "Choisir une photo" par défaut */
    #uploadPhotoButton {
        display: none;
    }
</style>

<script src="/Presentation/Assets/js/post.js"></script>