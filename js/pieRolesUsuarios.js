(function() {
    if (typeof datosUsuariosRol === 'undefined') {
        console.error('La variable datosUsuariosRol no está definida');
        return;
    }

    const labels = datosUsuariosRol.labels;
    const data = datosUsuariosRol.datos;

    const ctx = document.getElementById('usuariosRolChart');
    if (!ctx) return;

    const chart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Usuarios por rol',
                data: data,
                backgroundColor: [
                    'rgba(52, 152, 219, 0.7)',
                    'rgba(155, 89, 182, 0.7)',
                    'rgba(26, 188, 156, 0.7)',
                    'rgba(230, 126, 34, 0.7)',
                    'rgba(231, 76, 60, 0.7)' 
                ],
                borderColor: [
                    'rgba(52, 152, 219, 1)',
                    'rgba(155, 89, 182, 1)',
                    'rgba(26, 188, 156, 1)',
                    'rgba(230, 126, 34, 1)',
                    'rgba(231, 76, 60, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                title: {
                    display: true,
                    text: 'Distribución de usuarios por rol'
                }
            }
        }
    });
})();
