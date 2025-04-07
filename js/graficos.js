document.addEventListener("DOMContentLoaded", function() {
    // datos para grafico de linea 
    const lineChartData = {
        labels: datosLinea.labels,
        datasets: [{
            label: 'Gastos mensuales',
            data: datosLinea.datos,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1,
            fill: false
        }]
    };
    
    // datos para grafico comparativo
    const comparisonChartData = {
        labels: datosComparacion.categorias,
        datasets: [
            {
                label: 'Tus gastos',
                data: datosComparacion.datosUsuario,
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            },
            {
                label: 'Promedio estudiantes',
                data: datosComparacion.datosPromedio,
                backgroundColor: 'rgba(255, 99, 132, 0.7)'
            }
        ]
    };
    
    // datos para grafico de dispersion
    const scatterChartData = {
        datasets: [{
            label: 'Relación Ingresos-Gastos por mes',
            data: datosDispersion,
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
            pointRadius: 8,
            pointHoverRadius: 10
        }]
    };
    
    // datos para grafico de barras apiladas
    const stackedChartData = {
        labels: datosBarrasApiladas.meses,
        datasets: datosBarrasApiladas.categorias.map((categoria, index) => {
            const colorIndex = index % 5;
            const colores = [
                'rgba(255, 99, 132, 0.7)',
                'rgba(54, 162, 235, 0.7)',
                'rgba(255, 206, 86, 0.7)',
                'rgba(75, 192, 192, 0.7)',
                'rgba(153, 102, 255, 0.7)'
            ];
            return {
                label: categoria,
                data: datosBarrasApiladas.datos[categoria],
                backgroundColor: colores[colorIndex]
            };
        })
    };
    
    // grafico 1: grafico de linea
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: lineChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Gastos (€)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' €';
                        }
                    }
                }
            }
        }
    });
    
    // grafico 2: grafico comparativo 
    const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
    new Chart(comparisonCtx, {
        type: 'bar',
        data: comparisonChartData,
        options: {
            indexAxis: 'y',  // barras horizontales
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Importe (€)'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.x + ' €';
                        }
                    }
                }
            }
        }
    });
    
    // grafico 3: grafico de dispersion
    const scatterCtx = document.getElementById('scatterChart').getContext('2d');
    const scatterChart = new Chart(scatterCtx, {
        type: 'scatter',
        data: scatterChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Ingresos (€)'
                    },
                    beginAtZero: true
                },
                y: {
                    title: {
                        display: true,
                        text: 'Gastos (€)'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const data = context.dataset.data[context.dataIndex];
                            return data.label + ' - Ingresos: ' + data.x + '€, Gastos: ' + data.y + '€';
                        }
                    }
                }
            }
        }
    });
    
    
    // grafico 4: grafico de barras apiladas
    const stackedCtx = document.getElementById('stackedChart').getContext('2d');
    new Chart(stackedCtx, {
        type: 'bar',
        data: stackedChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Meses'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Importe (€)'
                    },
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' €';
                        }
                    }
                }
            }
        }
    });
});