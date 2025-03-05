document.addEventListener("DOMContentLoaded", function () {
    var selectCategoria = document.getElementById("categoriaSelect");
    var inputNueva = document.getElementById("categoriaNueva");

    selectCategoria.addEventListener("change", function () {
        if (this.value === "otra") {
            inputNueva.style.display = "inline-block";
            inputNueva.required = true;
        } else {
            inputNueva.style.display = "none";
            inputNueva.required = false;
        }
    });
});
