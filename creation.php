<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>EpiTrello</title>
        <link rel="stylesheet" type="text/css" href="styles.css">
    </head>
    <body>
        <h1>Création de compte</h1><br>
        <form method="post">
            <input type="text" name="pseudo" id="pseudo" placeholder="Your Name" required><br>
            <input type="email" name="email" id="email" placeholder="email : exemple@exemple.com" required><br>
            <input type="password" name="password" id="password" placeholder="Your password" required><br>
            <input type="password" name="password_verification" id="password_verification" placeholder="To verify your password" required><br>
            <input type="submit" name="creer" id="creer" value="Créer">
        </form>
        <style><?php include 'styles.css';?></style>
        <?php
            if (isset($_POST['creer'])) {
                $pseudo = $_POST['pseudo'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $verif = $_POST['password_verification'];
                if (empty($pseudo) || empty($email) || empty($password) || empty($verif)) {
                    echo "<script>alert('The form is not complete')</script>Please complete all the the parts<br>";
                    return;
                }
                if ($verif == $password) {
                    echo "Name : " . $pseudo . "<br>";
                    echo "email : " . $email . "<br>";
                    echo "Mot de passe : " . $password;
                } else {
                    echo '<p class=error>Not the same password</p>
                        <script>alert("Not the same password")</script>';

                }
            }
        ?>
    </body>
</html>