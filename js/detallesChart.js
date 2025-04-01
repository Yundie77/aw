document.addEventListener("DOMContentLoaded", function() {
    const detallesChartEl = document.getElementById('detallesChart');
    if (detallesChartEl) {
        const ctx = detallesChartEl.getContext('2d');
        
        const detallesChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                // Etiquetas y datos pasados desde PHP
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: [
                        '#FF6384', 
                        '#36A2EB', 
                        '#FFCE56', 
                        '#4BC0C0', 
                        '#9966FF', 
                        '#FF9F40'
                    ]
                }]
            },
            options: {
                responsive: false,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Distribución de gastos del grupo',
                        font: {
                            size: 29
                        },
                        padding: {
                            bottom: 25
                        }
                    },
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 15 
                            },
                            padding: 30
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                // Formateamos la etiqueta del tooltip
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + ' €';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }
});