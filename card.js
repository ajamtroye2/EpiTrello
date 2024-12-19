document.addEventListener("DOMContentLoaded", function () {
    const modifyButtons = document.querySelectorAll(".modify-carte");
    const overlay = document.getElementById("overlay");
    const closeOverlayButton = document.getElementById("close-overlay");

    modifyButtons.forEach(button => {
        button.addEventListener("click", function () {
            const cardId = this.getAttribute("data-card-id");
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "tab.php";
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "card_id";
            input.value = cardId;
            form.appendChild(input);

            document.body.appendChild(form);
            form.submit();
        });
    });
    closeOverlayButton.addEventListener("click", function () {
        overlay.classList.add("hidden");
    });
    overlay.addEventListener("click", function (event) {
        if (event.target === overlay) {
            overlay.classList.add("hidden");
        }
    });
});
