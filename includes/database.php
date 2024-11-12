<?php
    define('HOST', 'localhost');
    define('DB_NAME', 'account_register');
    define('USER', 'root');
    define('PASSWORD', '');

    try {
        $db = new PDO("mysql:host=" . HOST . ";dbname=" . DB_NAME, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "Error : " . $e->getMessage();
    }
?>
