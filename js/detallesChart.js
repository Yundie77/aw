document.addEventListener("DOMContentLoaded", function() {
    const detallesChartEl = document.getElementById('detallesChart');
    if (detallesChartEl) {
        const ctx = detallesChartEl.getContext('2d');
        const datosDelGrupo = {
            labels: ['Alimentación', 'Transporte', 'Entretenimiento', 'Otros'],
            datasets: [{
                data: [30, 20, 25, 25],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
            }]
        };
        const detallesChart = new Chart(ctx, {
            type: 'doughnut',
            data: datosDelGrupo,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución de Gastos del Grupo'
                    },
                    datalabels: {
                        formatter: (value, context) => {
                            let sum = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            let percentage = (value * 100 / sum).toFixed(1) + "%";
                            return percentage;
                        },
                        color: '#fff'
                    }
                }
            }
        });
    }
  });