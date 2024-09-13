<style>
    #canvas {
        border: 1px solid black;
        width: 320px; /* Taille plus petite du canvas */
        height: 240px;
    }

    .sticker {
        position: absolute;
        width: 100px;
        height: 100px;
        cursor: move;
    }
</style>

<div class="container mt-5">
    <h1 class="text-center">Prendre une photo avec un sticker</h1>

    <div class="text-center">
        <video id="video" width="320" height="240" autoplay></video> <!-- Taille plus petite pour la vidéo -->
        <canvas id="canvas" width="320" height="240" class="mt-3"></canvas>
    </div>

    <div class="d-flex justify-content-center mt-3">
        <button id="capture" class="btn btn-primary me-2">Prendre la photo</button>
        <button id="reset" class="btn btn-warning">Recommencer</button>
    </div>

    <div class="text-center mt-3">
        <h4>Ajoutez un sticker à la photo :</h4>
        <input type="file" id="stickerFile" accept="image/*" class="form-control mt-2" /> <!-- Visible pour choisir un sticker -->
        <img src="" alt="Sticker" id="stickerImage" class="sticker" style="display: none;">
    </div>

    <form id="photoForm" method="post" action="/post/save" class="text-center mt-3">
        <input type="hidden" id="photo" name="photo"> <!-- Contiendra la photo capturée en base64 -->
        <input type="hidden" id="stickerBase64" name="sticker"> <!-- Champ caché pour le sticker en base64 -->
        <button type="submit" class="btn btn-success">Envoyer la photo</button>
    </form>
</div>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1 class="text-center mb-4">Mes Posts</h1>

            <!-- Vérification s'il y a des posts -->
            <?php if (!empty($posts)): ?>
                <div class="row">
                    <?php foreach ($posts as $post): ?>
                        <div class="col-md-6 mb-4">
                            <div class="card shadow">
                                <div class="card-body text-center">
                                    <!-- Afficher l'image -->
                                    <img src="data:image/png;base64,<?= $post['image'] ?>" alt="Post Image" class="img-fluid" />
                                    <p class="mt-3">Posté le : <?= date('d-m-Y', strtotime($post['created_date'])) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center">Aucun post disponible.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="/Presentation/Assets/js/post.js"></script>