document.addEventListener("DOMContentLoaded", function () {
    const modifyButtons = document.querySelectorAll(".modify-carte");
    const overlay = document.getElementById("overlay");
    const closeOverlayButton = document.getElementById("close-overlay");

    // Ouvrir l'overlay au clic sur un bouton
    modifyButtons.forEach(button => {
        button.addEventListener("click", function () {
            const cardId = this.getAttribute("data-card-id");

            // Créer un formulaire temporaire pour envoyer les données en POST
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "tab.php";

            // Ajouter l'ID de la carte au formulaire
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "card_id";
            input.value = cardId;
            form.appendChild(input);

            // Ajouter le formulaire au DOM, soumettre, puis le supprimer
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Fermer l'overlay
    closeOverlayButton.addEventListener("click", function () {
        overlay.classList.add("hidden");
    });

    // Fermer l'overlay en cliquant en dehors de la zone de contenu
    overlay.addEventListener("click", function (event) {
        if (event.target === overlay) {
            overlay.classList.add("hidden");
        }
    });
});