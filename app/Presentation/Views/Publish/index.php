<?php
session_start(); 

$imagePath = $_SESSION['imagePath'] ?? ''; 
// unset($_SESSION['imagePath']);  // Clear the session variable
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Presentation/Assets/css/create-post.css">
    <title>Publish Post</title>
</head>
<body>

<div>
    <h1>Do you want to post this?</h1>
    <?php if ($imagePath): ?>
        <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Superimposed Image">
    <?php else: ?>
        <p>No image available to post.</p>
    <?php endif; ?>
    <form action="/publish/confirmation" method="post">
        <button type="submit">Publish</button>
    </form>
</div>

</body>
</html>

