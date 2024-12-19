<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}
$pseudo = $_SESSION['pseudo'];
$id = $_SESSION['id'];
$id_tab = $_SESSION['id_tab'] ?? null;
$cardId = $_SESSION['cardId'] ?? null;

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
    if (!empty($_POST['card_name'])) {
        $cc_id = $_SESSION['cardId'];
        $card_name = htmlspecialchars($_POST['card_name']);
        $card_com = htmlspecialchars($_POST['card_com']);
        $query = $db->prepare("UPDATE `carte` SET `name` = ? WHERE `id` = ?");
        $query->execute([$card_name, $cc_id]);
    }
    if (!empty($_POST['card_desc'])) {
        $cc_id = $_SESSION['cardId'];
        $card_desc= htmlspecialchars($_POST['card_desc']);
        $query = $db->prepare("UPDATE `carte` SET `description` = ? WHERE `id` = ?");
        $query->execute([$card_desc, $cc_id]);
    }
    if (!empty($_POST['card_com'])) {
        $cc_id = $_SESSION['cardId'];
        $card_com= htmlspecialchars($_POST['card_com']);
        $query = $db->prepare("INSERT INTO `commentaire` (`owner`, `id_card`, `text`) VALUES (?, ?, ?)");
        $query->execute([$id, $cc_id, $card_com]);
    }
    if (isset($_POST['delete_card']) && !empty($_POST['delete_card'])) {
        $delcard_id = intval($_POST['delete_card']);
        $stmt = $db->prepare('DELETE FROM carte WHERE id = ?');
        $stmt->execute([$delcard_id]);
    }
    if (!empty($_POST['card_id'])) {
        $_SESSION['cardId'] = $_POST['card_id'];
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    $_SESSION['cardId'] = null;
    if (!empty($_POST['list_name'])) {
        $name = htmlspecialchars($_POST['list_name']);
        $query = $db->prepare("INSERT INTO `list` (`id_tab`, `name`) VALUES (?, ?)");
        $query->execute([$id_tab, $name]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
    if (!empty($_POST['action'])) {
        $action = $_POST['action'];
        $list_id = intval($_POST['delete_list_id']);
        switch ($action) {
            case 'copy_list':
                $query = $db->prepare("INSERT INTO `list` (id_tab, name) SELECT id_tab, name FROM `list` WHERE id = ?");
                $query->execute([$list_id]);
                break;
            case 'move_list':
                // Logique pour déplacer la liste
                break;
            case 'delete_list':
                $query = $db->prepare("DELETE FROM `list` WHERE id = ?");
                $query->execute([$list_id]);
                break;
            case 'delete_all_cards':
                $query = $db->prepare("DELETE FROM `carte` WHERE id_list = ?");
                $query->execute([$list_id]);
                break;
        }
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
    <link rel="stylesheet" type="text/css" href="css/card.css">
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
                            echo"<div class='carte'>"
                                . htmlspecialchars($carte["name"])
                                . "<button class='modify-carte'
                                        data-card-id='".$carte['id']."'>
                                🖉</button></div>";
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
                            <button class='add-carte-button' data-list-id='".$list['id']."'>Ajouter une carte</button>
                            <form method='POST'>
                                <input type='hidden' name='delete_list_id' value='{$list["id"]}'>
                                <button type='submit' name='action' value='copy_list'>Copier la liste</button>",
                                //<button type='submit' name='action' value='move_list'>Déplacer la liste</button>
                                "<button type='submit' name='action' value='delete_list'>Supprimer</button>
                                <button type='submit' name='action' value='delete_all_cards'>Supprimer toutes les cartes de la liste</button>
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
    <?php
        if ($cardId) {
            $query = $db->query("SELECT * FROM carte WHERE id = " . $cardId);
            if ($query->rowCount() == 1) {
                $card_data = $query->fetch(PDO::FETCH_ASSOC);
            }
        }
    ?>
    <div id="overlay" class="<?php echo isset($_SESSION['cardId']) && $_SESSION['cardId'] ? '' : 'hidden'; ?>">
        <div class="overlay-content">
            <form method="POST" id="overlay-form">
                <input type="hidden" id="card_id" name="card_id" value="<?= htmlspecialchars('')?>">
                <input type="text" id="card-name" name="card_name" value="<?= $card_data['name'] ?>">
                <h4>Description<h4>
                <input type="text" id="card-desc" name="card_desc" value="<?= $card_data['description'] ?>"></br>
                <?php $query = $db->prepare("SELECT description FROM carte WHERE id = ?");
                        $query->execute([$card_data['id']]);
                        $desc = $query->fetch(PDO::FETCH_ASSOC);
                        echo "<p>".htmlspecialchars($desc["description"])."</p>";
                ?>
                <div class="comment-content">
                    <h4>commentaires<h4>
                    <input type="text" id="card-com" name="card_com"></br></br>
                    <?php 
                        $query = $db->prepare("SELECT * FROM commentaire WHERE id_card = ?");
                        $query->execute([$card_data['id']]);
                        while ($comentaire = $query->fetch(PDO::FETCH_ASSOC)) {
                            echo "
                            <div class='comment-box'>
                                <div class='header'>
                                    <span>".htmlspecialchars($pseudo)."</span>
                                    <span class='date'>".htmlspecialchars($comentaire['date'])."</span>
                                </div>
                                <p>".htmlspecialchars($comentaire['text'])."</p>
                            </div>";
                        }
                    ?>
                </div>
                <button type='submit' name='delete_card' value="<?= htmlspecialchars($card_data['id']) ?>">Supprimer</button>
                <button type="submit" id="close-overlay">Fermer</button>
            </form>
        </div>
    </div>
    <script src="tab.js"></script>
    <script src="card.js" defer></script>
</body>
</html>