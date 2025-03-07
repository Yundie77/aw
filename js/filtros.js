document.addEventListener("DOMContentLoaded", function () {
    // Obtener elementos del formulario
    let tipoSelect = document.getElementById("tipo");
    let categoriaSelect = document.getElementById("categoria");

    // Detectar cambios en los selects y enviar el formulario automáticamente
    tipoSelect.addEventListener("change", function () {
        document.getElementById("filtrosForm").submit();
    });

    categoriaSelect.addEventListener("change", function () {
        document.getElementById("filtrosForm").submit();
    });
});
