<?php

?>

<h1>salut</h1>

<button id="redirectButton">
    Users
</button>
<div>
    <?php include(__DIR__ . "/../Users/Components/ListUsers.php") ?>
</div>

<div></div>

<script>
    document.getElementById("redirectButton").addEventListener("click", function() {
        window.location.href = "Views/Users/index.php";
    });
</script>