const showAddListButton = document.getElementById('showAddListButton');
const listInputContainer = document.getElementById('listInputContainer');
const showAddCardButton = document.getElementById('add-carte-button');
const cardInputContainer = document.getElementById('cardInputContainer');
const closeButton = document.getElementById("close-btn");
const closeButton2 = document.getElementById("close-btn2");

showAddListButton.addEventListener('click', () => {
    showAddListButton.style.display = "none";
    listInputContainer.style.display = "block";
});
closeButton.addEventListener("click", (event) => {
    event.preventDefault();
    listInputContainer.style.display = "none";
    showAddListButton.style.display = "block";
});
if (showAddCardButton && cardInputContainer) {
    showAddCardButton.addEventListener("click", (event) => {
        showAddCardButton.style.display = "none";
        cardInputContainer.style.display = "block";
    });

    closeButton2.addEventListener("click", (event) => {
        event.preventDefault();
        cardInputContainer.style.display = "none";
        showAddCardButton.style.display = "block";
    });
}

const actionButtons = document.querySelectorAll('.actions-menu-button');
const menus = document.querySelectorAll('.actions-menu');
actionButtons.forEach(button => {
    button.addEventListener('click', () => {
        const menu = button.nextElementSibling;
        menus.forEach(m => m.style.display = 'none');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });
});
document.addEventListener('click', (event) => {
    if (![...actionButtons].includes(event.target) && !event.target.closest('.actions-menu')) {
        menus.forEach(menu => menu.style.display = 'none');
    }
});
const deleteMenuButton = document.getElementById('delete-menu-button');
const deleteMenu = document.getElementById('delete-menu');
if (deleteMenuButton && deleteMenu) {
    deleteMenuButton.addEventListener('click', () => {
        console.log('Bouton cliqué');
        const isVisible = deleteMenu.style.display === 'block';
        deleteMenu.style.display = isVisible ? 'none' : 'block';
    });
    document.addEventListener('click', (event) => {
        if (
            event.target !== deleteMenuButton &&
            !deleteMenu.contains(event.target)
        ) {
            console.log('Clic en dehors du menu');
            deleteMenu.style.display = 'none';
        }
    });
} else {
    console.error('Un élément est manquant : vérifiez les IDs');
}
