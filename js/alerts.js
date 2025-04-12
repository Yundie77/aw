// Escucha el evento DOMContentLoaded para inicializar el manejo de formularios
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form'); // Selecciona todos los formularios en la página
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            // Simula una operación exitosa (reemplazar con la lógica real de éxito)
            event.preventDefault(); // Previene el envío real del formulario para demostración
            setTimeout(() => {
                showSuccessAlert(); // Llama a la alerta de éxito después de la operación
            }, 200); // Simula un retraso para la operación
        });
    });
});

// Muestra una alerta para advertir que solo los administradores pueden realizar ciertas acciones
function showAdminAlert() {
    alert("Solo los administradores pueden agregar usuarios, modificar o eliminar grupos.");
}

// Muestra una alerta indicando que la operación fue exitosa
function showSuccessAlert() {
    alert("Operación exitosa");
}

// Muestra una alerta indicando que ocurrió un error en la operación
function showErrorAlert() {
    alert("Error: No se pudo completar la operación.");
}