<?php 
    $pseudo = "admin";
    $email = "admin@gmail.com";
    $age = 18;
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>EpiTrello</title>
    </head>
    <body>
        <p>Pseudo : <?= $pseudo; ?></p>
        <p>Email : <?= $email; ?></p>
        <p>Age : <?= $age; ?></p>

        <form>
            <input type="text" name="pseudo" id="pseudo">
            <input type="email" name="email" id="email">
            <input type="password" name="password" id="password">
            <input type="submit" name="créer" id="creer">
        </form>

    </body>
</html>