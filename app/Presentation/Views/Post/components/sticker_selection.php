<div class="sticker-selection">
    <h3>Sélectionner un sticker</h3>
    <ul id="sticker-list">
        <?php
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