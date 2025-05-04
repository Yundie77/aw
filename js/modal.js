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
                    // Store message in sessionStorage before reload
                    sessionStorage.setItem('modalMessage', JSON.stringify({type: 'success', text: data.success}));
                    modal.style.display = 'none';
                    location.reload();
                } else if (data.error) {
                    sessionStorage.setItem('modalMessage', JSON.stringify({type: 'error', text: data.error}));
                    location.reload();
                }
            })
        });
    });

    // Show modal message after reload if exists
    const msg = sessionStorage.getItem('modalMessage');
    if (msg) {
        const {type, text} = JSON.parse(msg);
        let div = document.createElement('div');
        div.className = type === 'success' ? 'mensaje-exito' : 'mensaje-error';
        div.textContent = text;
        // Insert below the group blocks if present, else fallback to top of main
        const grupoList = document.querySelector('.grupo-list');
        if (grupoList && grupoList.parentNode) {
            grupoList.parentNode.insertBefore(div, grupoList.nextSibling);
        } else {
            const main = document.querySelector('main');
            if (main) main.insertBefore(div, main.firstChild);
        }
        sessionStorage.removeItem('modalMessage');
    }
});