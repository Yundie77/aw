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
    const modalsForms = document.querySelectorAll('.modal-content form');
    modalsForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            if (form.dataset.ajax === "true") { // Only handle forms marked for AJAX
                e.preventDefault();
                const formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessAlert(); // Use the alert from alerts.js
                        const modal = form.closest('.modal');
                        modal.style.display = 'none';
                        location.reload();
                    } else {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error al procesar la solicitud.');
                });
            }
        });
    });
});