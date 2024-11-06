<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>EpiTrello</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <h1>Connexion au site internet</h1><br>
        <form method="post">
            <input type="email" name="email" id="email" placeholder="email : exemple@exemple.com" required><br>
            <input type="password" name="password" id="password" placeholder="Your password" required><br>
            <input type="submit" name="connection" id="connection" value="Connection">
            <p>Errors :</p>
        </form>
        <style><?php include 'styles.css';?></style>

        <?php
            //afficher utilisateur dans base de donnée
            include 'database.php';
            global $db;

            if (isset($_POST['connection'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $query = $db->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
                $query->execute([$email, $password]);
                if ($query->rowCount() > 0) {
                    echo "<p>Connecté, avec ".$email." et le mot de passe : ".$password."</p>";
                } else {
                    $email_check = $db->prepare("SELECT * FROM users WHERE email = ?");
                    $email_check->execute([$email]);
                    if ($email_check->rowCount() > 0) {
                        echo "<p>Mauvais mot de passe</p>";
                    } else {
                        echo "<p>Adresse email incorrecte</p>";
                    }
                }
            }
        ?>
    </body>
</html>