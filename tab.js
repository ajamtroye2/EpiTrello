const showAddListButton = document.getElementById('showAddListButton');
const listInputContainer = document.getElementById('listInputContainer');
const closeButton = document.getElementById("close-btn");

showAddListButton.addEventListener('click', () => {
    showAddListButton.style.display = "none";
    listInputContainer.style.display = "block";
});
closeButton.addEventListener("click", (event) => {
    event.preventDefault();
    listInputContainer.style.display = "none";
    showAddListButton.style.display = "block";
});
document.querySelectorAll('.add-carte-button').forEach(button => {
    button.addEventListener('click', (event) => {
        const listId = button.getAttribute('data-list-id');
        const cardInputContainer = document.querySelector(`.cardInputContainer[data-list-id='${listId}']`);
        
        button.style.display = "none";
        cardInputContainer.style.display = "block";
    });
});

document.querySelectorAll('.close-btn2').forEach(button => {
    button.addEventListener('click', (event) => {
        event.preventDefault();
        const listId = button.getAttribute('data-list-id');
        const cardInputContainer = document.querySelector(`.cardInputContainer[data-list-id='${listId}']`);
        const addCardButton = document.querySelector(`.add-carte-button[data-list-id='${listId}']`);

        cardInputContainer.style.display = "none";
        addCardButton.style.display = "block";
    });
});

const actionButtons = document.querySelectorAll('.actions-menu-button');

actionButtons.forEach(button => {
    button.addEventListener('click', (event) => {
        event.stopPropagation();
        const parentList = button.closest('.list');
        const menu = parentList.querySelector('.actions-menu');
        document.querySelectorAll('.actions-menu').forEach(m => {
            if (m !== menu) {
                m.style.display = 'none';
            }
        });
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    });
});
document.addEventListener('click', () => {
    document.querySelectorAll('.actions-menu').forEach(menu => {
        menu.style.display = 'none';
    });
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
