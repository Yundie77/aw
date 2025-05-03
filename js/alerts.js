// Escucha el evento DOMContentLoaded para inicializar el manejo de formularios
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form'); // Selecciona todos los formularios en la página
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            event.preventDefault(); // Previene el envío real del formulario para demostración
            setTimeout(() => {
                form.submit(); // Permite que el formulario se envíe al servidor
            }, 200); // Simula un retraso para la operación
        });
    });
});

function showAlert(message) {
    alert(message); // Muestra una alerta con el mensaje proporcionado
}