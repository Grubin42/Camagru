<!-- /Presentation/Views/Post/components/capture_section.php -->

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