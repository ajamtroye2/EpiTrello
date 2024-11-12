<?php session_start(); ?>
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
        </form>
        <div class="col"><button id="creer">Sign up - EpiTrello</button></div>
        <script>
            document.getElementById("creer").addEventListener("click", function() {window.location.href = "creation.php";});
        </script>
        <style><?php include 'css/styles.css';?></style>

        <?php
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
                        $_SESSION['email'] = $email;
                        $_SESSION['pseudo'] = $pass_q['pseudo'];
                        $_SESSION['id'] = $pass_q['id'];
                        echo "<script>window.location.href = 'menu.php';</script>";
                        exit();
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