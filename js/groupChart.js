// groupChart.js

document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('graficoGastos').getContext('2d');

    fetch('get_datos_grafico.php?id=' + grupoId)
        .then(response => response.json())
        .then(data => {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Gastos por usuario',
                        data: data.valores,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar los datos del gráfico:', error);
        });
});