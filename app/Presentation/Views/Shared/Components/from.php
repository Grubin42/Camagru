<!-- reusable form component -->
<!-- 

arguments: 
    $inputFields: array of input fields
    $error: error message
    $buttonText: text for the submit button
    $formAction: action attribute of the form
  -->

<?php   // TODO: add field disabled
if ($inputFields): ?>
    <form action="<?= $formAction ?>" method="post" class="flex-column-gap-10 flex-center-all">
        <?php foreach ($inputFields as $inputField): ?>
            <div class="flex-column">
                <label for="<?= $inputField['name'] ?>"><?= $inputField['label'] ?>:</label>
                <input type="<?= $inputField['type'] ?>" id="<?= $inputField['name'] ?>" name="<?= $inputField['name'] ?>"
                    required>
            </div>
        <?php endforeach; ?>
        <?php if ($error): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>
        <div class="button-submit-container">
            <button type="submit"><?= $buttonText ?></button>
        </div>
    </form>

<?php else: ?>
    <p>There are no input fields to display.</p>
<?php endif; ?>