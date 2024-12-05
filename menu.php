<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: connexion.php");
    exit();
}
$email = $_SESSION['email'];
$pseudo = $_SESSION['pseudo'];
$id = $_SESSION['id'];
$_SESSION['cardId'] = null;

include 'includes/database.php';
global $db;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_tableau'])) {
    $name = $_POST['tables_name'] ?? 'Nouveau Tableau';
    $color = intval($_POST['background']);
    $query = $db->prepare("INSERT INTO `Tab`(`owner`, `name`, `background`) VALUES (?, ?, ?)");
    $query->execute([$id, $name, $color]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$query = $db->prepare("SELECT COUNT(*) AS total FROM Tab WHERE owner = ?");
$query->execute([$id]);
$totalTableaux = $query->fetch(PDO::FETCH_ASSOC)['total'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu des tableaux</title>
    <link rel="stylesheet" type="text/css" href="css/menu.css">
</head>
<body>
    <a href="?deconnexion=true" class="deconnexion-btn">
        <img src="img/logout.png" alt="Déconnexion">
    </a>
    <h1>Bienvenue <?= htmlspecialchars($pseudo) ?> !</h1><br>
    <form method="POST" class="image-container" action='tab.php'>
        <?php
            if (isset($_GET['deconnexion'])) {
                $_SESSION['email'] = null;
                header("Location: connexion.php");
                exit();
            }
            $query = $db->prepare("SELECT * FROM Tab WHERE owner = ?");
            $query->execute([$id]);
            while ($tab = $query->fetch(PDO::FETCH_ASSOC)) {
                $backgroundColor = match($tab['background']) {
                    1 => '#FF5733',
                    2 => '#33FF57',
                    3 => '#3357FF',
                    4 => '#F1C40F',
                    5 => '#8E44AD',
                    6 => '#2ECC71',
                    default => 'lightblue'
                };
                echo "
                    <button type='submit' class='tables' name='tables' style='background-color: $backgroundColor;' value='{$tab['id']}'>
                        <div class='tables-name'>{$tab['name']}</div>
                    </button>";
            }
            if ($totalTableaux < 15) {
                echo '<button type="button" id="addTableauBtn" class="plus-button"></button>';
            } else {
                echo "<script>alert('Nombre maximum de tableaux atteint (15).');</script>";
            }
        ?>
    </form>

    <div class="overlay" id="overlay" name="overlay"></div>
    <div class="modal" id="addTableauModal">
    <form method="POST" action="">
        <div class="modal-header">
            <h2>Créer un tableau</h2>
            <button class="close-btn" id="closeModalBtn">&times;</button>
        </div>
        <div class="color-container">
            <div class="color-rect" style="background-color: #FF5733;" data-color="1"></div>
            <div class="color-rect" style="background-color: #33FF57;" data-color="2"></div>
            <div class="color-rect" style="background-color: #3357FF;" data-color="3"></div>
            <div class="color-rect" style="background-color: #F1C40F;" data-color="4"></div>
            <div class="color-rect" style="background-color: #8E44AD;" data-color="5"></div>
            <div class="color-rect" style="background-color: #2ECC71;" data-color="6"></div>
        </div>
        <input type="hidden" name="background" id="background" value="1">
        <input type="text" name="tables_name" id="tables_name" placeholder="Nom du tableau" required>
        <button type="submit" name="add_tableau" class="create-button">Créer</button>
    </form>
    </div>

    <script>
        const addTableauBtn = document.getElementById('addTableauBtn');
        const addTableauModal = document.getElementById('addTableauModal');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const overlay = document.getElementById('overlay');
        const colorSelectors = document.querySelectorAll('.color-selector');
        let selectedColor = null;
        const colorRects = document.querySelectorAll('.color-rect');
        const backgroundInput = document.getElementById('background');

        colorRects.forEach(rect => {rect.addEventListener('click', () => {
                colorRects.forEach(r => r.classList.remove('selected'));
                rect.classList.add('selected');
                backgroundInput.value = rect.getAttribute('data-color');
            });
        });
        addTableauBtn.addEventListener('click', () => {
            addTableauModal.style.display = 'block';
            document.getElementById('overlay').classList.add('active');
        });
        closeModalBtn.addEventListener('click', () => {
            addTableauModal.style.display = 'none';
            document.getElementById('overlay').classList.remove('active');
        });
        overlay.addEventListener('click', () => {
            addTableauModal.style.display = 'none';
            document.getElementById('overlay').classList.remove('active');
        });
        colorSelectors.forEach(selector => {
            selector.addEventListener('click', () => {
                colorSelectors.forEach(s => s.classList.remove('selected'));
                selector.classList.add('selected');
                selectedColor = selector.getAttribute('data-color');
                document.getElementById('backgroundColor').value = selectedColor;
            });
        });
    </script>
</body>
</html>
