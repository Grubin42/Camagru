<?php
if ($title): ?>
    <h1 class="title "><?= $title ?></h1>
    <hr />
<?php endif; ?>

<?php if ($componentPath): ?>
    <div class="flex-container">
        <?php renderComponent($componentPath, ['error' => $error]); ?>
    </div>
<?php endif; ?>