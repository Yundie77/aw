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

    const editId = document.getElementById('edit-id');
    const editTipo = document.getElementById('edit-tipo');
    const editMonto = document.getElementById('edit-monto');
    const editFecha = document.getElementById('edit-fecha');
    const editComentario = document.getElementById('edit-comentario');

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
