<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>EpiTrello</title>
        <link rel="stylesheet" type="text/css" href="css/styles.css">
    </head>
    <body>
        <h1>Connexion au site internet</h1><br>
        <form method="post">
            <input type="email" name="email" id="email" placeholder="email : exemple@exemple.com" required><br>
            <input type="password" name="password" id="password" placeholder="Your password" required><br>
            <input type="submit" name="connection" id="connection" value="Connection">
            <p>Errors :</p>
        </form>
        <style><?php include 'css/styles.css';?></style>

        <?php
            //afficher utilisateur dans base de donnée
            include 'includes/database.php';
            global $db;

            if (isset($_POST['connection'])) {
                $email = $_POST['email'];
                $password = $_POST['password'];
                $email_q = $db->prepare("SELECT * FROM users WHERE email = ?");
                $email_q->execute([$email]);
            
                if ($email_q->rowCount() > 0) {
                    $pass_q = $email_q->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $pass_q['password'])) {
                        echo "<p>Connecté, avec ".$email."</p>";
                    } else {
                        echo "<p>Mauvais mot de passe</p>";
                    }
                } else {
                    echo "<p>Adresse email incorrecte</p>";
                }
            }
        ?>
    </body>
</html>