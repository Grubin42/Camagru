<!-- /Presentation/Views/Post/index.php -->

<div class="main-container">
    <!-- Composant Messages d'Erreur -->
    <?php include 'components/error_messages.php'; ?>

    <!-- Colonne de gauche -->
    <div class="left-column">
        <!-- Composant Capture Section -->
        <?php include 'components/capture_section.php'; ?>

        <!-- Composant Sticker Selection -->
        <?php include 'components/sticker_selection.php'; ?>

        <!-- Composant Result Section -->
        <?php include 'components/result_section.php'; ?>
    </div>

    <!-- Colonne de droite -->
    <div class="right-column">
        <!-- Composant User Posts -->
        <?php include 'components/user_posts.php'; ?>
    </div>
</div>

<!-- Inclure le fichier JavaScript séparé -->
<script src="/Presentation/Assets/js/post.js"></script>