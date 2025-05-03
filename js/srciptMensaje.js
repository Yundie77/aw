document.addEventListener('DOMContentLoaded', function () {
    const modals = [
        { id: 'modal-agregar-miembro', action: 'agregar_miembro.php' },
        { id: 'modal-agregar-grupo', action: 'agregar_grupo.php' },
        { id: 'modal-modificar-grupo', action: 'modificar_grupo.php' },
        { id: 'modal-eliminar-grupo', action: 'eliminar_grupo.php' }
    ];

    modals.forEach(modalConfig => {
        const modal = document.getElementById(modalConfig.id);
        if (modal) {
            const form = modal.querySelector('form'); // Select the form inside the modal
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent traditional form submission

                    const formData = new FormData(this); // Get form data

                    fetch(modalConfig.action, {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json(); // Attempt to parse JSON
                    })
                    .then(data => {
                        if (data.success) {
                            showAlert(data.success); // Show success message
                            modal.style.display = 'none'; // Close the modal
                            location.reload(); // Reload the page to reflect changes
                        } else if (data.error) {
                            showAlert(data.error); // Show error message
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Error al procesar la solicitud.'); // Show alert for general error
                    });
                });
            } else {
                console.warn(`Form inside '${modalConfig.id}' not found.`); // Debug log
            }
        } else {
            console.warn(`Modal with ID '${modalConfig.id}' not found.`); // Debug log
        }
    });
});