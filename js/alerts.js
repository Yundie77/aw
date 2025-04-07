// Trigger showSuccessAlert after a successful form submission
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function (event) {
            // Simulate a successful operation (replace this with actual success logic)
            event.preventDefault(); // Prevent actual form submission for demonstration
            setTimeout(() => {
                showSuccessAlert(); // Call success alert after operation
            }, 200); // Simulate delay for operation
        });
    });
});

function showAdminAlert() {
    alert("Solo los administradores pueden agregar usuarios, modificar o eliminar grupos.");
}

function showSuccessAlert() {
    alert("Operación exitosa");
}