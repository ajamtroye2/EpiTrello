<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}
$pseudo = $_SESSION['pseudo'];
$id = $_SESSION['id'];
$id_tab = $_SESSION['id_tab'];

include 'includes/database.php';
include 'includes/enum.php';
global $db;

if (isset($_GET['menu'])) {
    header("Location: menu.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['tables'])) {
        $id_tab = $_POST['tables'] ?? null;
        $_SESSION['id_tab'] = $id_tab;
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $id_tab = $_SESSION['id_tab'];
    if (!empty($_POST['list_name'])) {
        $name = htmlspecialchars($_POST['list_name']);
        $query = $db->prepare("INSERT INTO `list` (`id_tab`, `name`) VALUES (?, ?)");
        $query->execute([$id_tab, $name]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    if (!empty($_POST['delete_list_id'])) {
        $list_id = intval($_POST['delete_list_id']);
        $query = $db->prepare("DELETE FROM `list` WHERE `id` = ?");
        $query->execute([$list_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    if (!empty($_POST['delete_tab_id'])) {
        $query = $db->prepare("DELETE FROM `Tab` WHERE `id` = ?");
        $query->execute([$id_tab]);
        header("Location: menu.php");
        exit();
    }
    if (!empty($_POST['add_carte'])) {
        $list_id = intval($_POST['add_carte']);
        $card_name = htmlspecialchars($_POST['card_name']);
        $query = $db->prepare("INSERT INTO `carte` (`id_list`, `name`) VALUES (?, ?)");
        $query->execute([$list_id, $card_name   ]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$query = $db->prepare("SELECT COUNT(*) AS total FROM list WHERE id_tab = ?");
$query->execute([$id_tab]);
$totallist = $query->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu des Listes</title>
    <link rel="stylesheet" type="text/css" href="css/tab.css">
    <?php
        $query = $db->query("SELECT background FROM Tab WHERE id = " . $id_tab);
        echo "<style>body{background-color:".match($query->fetch(PDO::FETCH_ASSOC)['background']) {
            1 => '#FF5733',
            2 => '#33FF57',
            3 => '#3357FF',
            4 => '#F1C40F',
            5 => '#8E44AD',
            6 => '#2ECC71',
            default => 'lightblue'
        }."}</style>".$query->fetch(PDO::FETCH_ASSOC);
    ?>
</head>
<body>
    <div class='delete-menu' id='delete-menu'>
        <form method='POST'>
            <button type='submit'>Modiffier le fond d'écran</button>
        </form></br>
        <form method='POST'>
            <input type='hidden' name='delete_tab_id' value="1">
            <button type='submit'>Supprimer</button>
        </form>
    </div>
        <?php
            if ($id_tab) {
                $query = $db->query("SELECT * FROM Tab WHERE id = " . $id_tab);
                if ($query->rowCount() > 0) {
                    $tabData = $query->fetch(PDO::FETCH_ASSOC);
                    echo "<div class='header-banner'><h1>
                    <a href='?menu=true' class='menu-btn' style='text-decoration: none;'>
                        <img src='img/EpiTrellologo.png' alt='EpiTrello' draggable='false'>
                    </a>" . $tabData['name'] . "<button class='delete-menu-button' id='delete-menu-button'>...</button></h1></div>";
                }
                $query = $db->prepare("SELECT * FROM list WHERE id_tab = ?");
                $query->execute([$id_tab]);
                while ($list = $query->fetch(PDO::FETCH_ASSOC)) {
                    echo "<div class= 'container-list'>
                        <div class='list' id='list-".$list['id']."'>
                            <span>{$list["name"]}</span>";
                        $cartesQuery = $db->prepare("SELECT * FROM carte WHERE id_list = ?");
                        $cartesQuery->execute([$list["id"]]);
                        while ($carte = $cartesQuery->fetch(PDO::FETCH_ASSOC)) {
                            echo "<div class='carte'>" . htmlspecialchars($carte["name"]) . "<button class='modify-carte'>🖉</button></div>";
                    }
                    echo "
                        <button class='add-carte-button' data-list-id='".$list['id']."'>+ Ajouter une carte</button>
                        <div class='cardInputContainer added' data-list-id='".$list['id']."'>
                            <form method='POST'>
                                <input type='hidden' name='add_carte' value='".$list['id']."'>
                                <input type='text' class='name' name='card_name' placeholder='Nom de la carte' required>
                                <button type='submit' class='create-list-btn'>Ajouter une carte</button>
                                <button class='close-btn close-btn2' data-list-id='".$list['id']."'>x</button>
                            </form>
                        </div>                    
                        <button class='actions-menu-button'>...</button>
                        <div class='actions-menu'>
                            <h4>Liste des actions</h4>
                            <form method='POST'>
                                <input type='hidden' name='delete_list_id' value='{$list["id"]}'>
                                <button type='submit'>Supprimer</button>
                            </form>
                        </div>
                    </div>";
                }
                echo '<button id="showAddListButton">+ Ajouter une liste</button>';
            }
        ?>
        <div id="listInputContainer" class="added">
            <form method="POST">
                <input type="text" class="name" name="list_name" placeholder="Nom de la liste" required>
                <button type="submit" class="create-list-btn">Ajouter une liste</button>
                <button id="close-btn" class="close-btn">x</button>
            </form>
        </div>
    </div>
    <script src="tab.js"></script>
</body>
</html>
