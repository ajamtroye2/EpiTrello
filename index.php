<?php
session_start();
if (isset($_SESSION['email'])) {
    header("Location: menu.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>EpiTrello</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <div class="connection"><button id="connection"></button></div>
        <h1>Bienvenue</h1>
        <h2>Sur EpiTrello</h2>
        <div class="create"><button id="create">Create an account - EpiTrello</button></div>
        <script>
            document.getElementById("create").addEventListener("click", function() {window.location.href = "creation.php";});
            document.getElementById("connection").addEventListener("click", function() {window.location.href = "connexion.php";});
        </script>
    </body>
</html>