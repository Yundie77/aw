document.addEventListener("DOMContentLoaded", function () {
    let debounceTimer;
    let searchInput = document.getElementById("search");

    searchInput.addEventListener("keyup", function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            document.getElementById("filtrosForm").submit();
        }, 600); // Espera 600ms después de la última tecla antes de enviar
    });
});
