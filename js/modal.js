// Funciones para abrir y cerrar modales
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}

// Cierra el modal si se hace clic fuera del contenido
window.onclick = function(event) {
    const modals = document.getElementsByClassName('modal');
    for (let i = 0; i < modals.length; i++) {
        if (event.target == modals[i]) {
            modals[i].style.display = 'none';
        }
    }
}

// Interceptar el envío de las formas en los modales y enviarlas vía AJAX
document.addEventListener('DOMContentLoaded', function () {
    const modalsForms = document.querySelectorAll('.modal-content-grupo form');

    modalsForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const modal = form.closest('.modal');
                const resultMessage = document.getElementById('mensaje-resultado');

                if (data.success) {
                    resultMessage.innerHTML = `<p style="color:green;">${data.success}</p>`;
                    modal.style.display = 'none'; // Cerrar el modal
                    window.location.href = `${window.location.pathname}?mensaje=success`; 
                } else if (data.error) {
                    resultMessage.innerHTML = `<p style="color:red;">${data.error}</p>`;
                    window.location.href = `${window.location.pathname}?mensaje=error`;
                }
            })
        });
    });
});