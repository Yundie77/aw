document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".btn-edit");
    const modal = document.getElementById("editGastoModal");

    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            openEditModal(this);
        });
    });

    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    };
});

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "none";
    }
}

function openEditModal(button) {
    const modal = document.getElementById('editGastoModal');
    if (!modal) {
        console.error('Modal no encontrado');
        return;
    }

    const editId = modal.querySelector('input[name="id"]');
    const editMonto = modal.querySelector('input[name="monto"]');
    const editFecha = modal.querySelector('input[name="fecha"]');
    const editComentario = modal.querySelector('textarea[name="comentario"]');

    if (!editId || !editMonto || !editFecha || !editComentario) {
        console.error('Campos del modal faltantes');
        return;
    }

    // Asignación segura (evita undefined y números forzados por el DOM)
    editId.value = button.dataset.id || '';
    editMonto.value = button.dataset.monto || '';
    editFecha.value = button.dataset.fecha || '';
    editComentario.value = button.dataset.comentario || '';

    modal.style.display = 'block';
}
