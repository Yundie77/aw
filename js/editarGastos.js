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
    document.getElementById(modalId).style.display = "none";
}

function openEditModal(button) {
    const modal = document.getElementById('editGastoModal');
    if (!modal) {
        console.error('Modal element not found');
        return;
    }

    const editId = modal.querySelector('input[name="id"]');
    const editTipo = modal.querySelector('select[name="tipo"]');
    const editMonto = modal.querySelector('input[name="monto"]');
    const editFecha = modal.querySelector('input[name="fecha"]');
    const editComentario = modal.querySelector('textarea[name="comentario"]');

    if (!editId || !editTipo || !editMonto || !editFecha || !editComentario) {
        console.error('One or more modal fields are missing');
        return;
    }

    editId.value = button.dataset.id;
    editTipo.value = button.dataset.tipo;
    editMonto.value = button.dataset.monto;
    editFecha.value = button.dataset.fecha;
    editComentario.value = button.dataset.comentario;

    modal.style.display = 'block';
}

document.querySelectorAll('.modal .close').forEach(closeButton => {
    closeButton.addEventListener('click', () => {
        closeButton.closest('.modal').style.display = 'none';
    });
});
