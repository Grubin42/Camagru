<!-- /Presentation/Views/Post/components/sticker_selection.php -->

<div class="sticker-selection">
    <h3>Sélectionner un sticker à ajouter</h3>
    <ul id="sticker-list">
        <?php
        // Chemin vers le répertoire contenant les stickers
        $stickerDir = __DIR__ . '/../../../Assets/images/'; 
        $stickers = glob($stickerDir . '*.{jpg,png,gif,jpeg}', GLOB_BRACE);
        foreach ($stickers as $stickerPath) {
            $stickerName = basename($stickerPath);
            $stickerUrl = '/Presentation/Assets/images/' . $stickerName;
            echo '<li><img src="' . $stickerUrl . '" alt="' . $stickerName . '" class="sticker"></li>';
        }
        ?>
    </ul>
</div>