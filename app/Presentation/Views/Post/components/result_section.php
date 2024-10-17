<!-- /Presentation/Views/Post/components/result_section.php -->

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