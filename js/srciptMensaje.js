document.getElementById('form-agregar-miembro').addEventListener('submit', function(e) {
    e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    const formData = new FormData(this); // Obtiene los datos del formulario

    fetch('agregar_miembro.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const mensajeResultado = document.getElementById('mensaje-resultado');
        if (data.success) {
            mensajeResultado.innerHTML = `<p style="color: green;">${data.success}</p>`;
        } else if (data.error) {
            mensajeResultado.innerHTML = `<p style="color: red;">${data.error}</p>`;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('mensaje-resultado').innerHTML = `<p style="color: red;">Error al procesar la solicitud.</p>`;
});
});