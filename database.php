<?php
    define('HOST', 'localhost');
    define('DB_NAME', 'account_register');
    define('USER', 'root');
    define('PASSWORD', '');

    try {
        $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query = $db->query("SELECT * FROM users WHERE 1");
        if ($query->rowCount() > 0) {
            while ($user = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "Utilisateur : " . $user['pseudo'] . "<br>mot de passe : " . $user['password'] . "<br>Email : " . $user['email'] . "<br>";
            }
        } else {
            echo "Aucun utilisateur trouvé";
        }
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
?>
