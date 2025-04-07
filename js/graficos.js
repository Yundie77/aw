document.addEventListener("DOMContentLoaded", function() {
    // Verificar si hay datos disponibles para cada gráfico
    const hayDatosLinea = datosLinea.labels && datosLinea.labels.length > 0;
    const hayDatosComparacion = datosComparacion.categorias && datosComparacion.categorias.length > 0;
    const hayDatosDispersion = datosDispersion && datosDispersion.length > 0;
    const hayDatosBarras = datosBarrasApiladas.meses && datosBarrasApiladas.meses.length > 0;
    
    // Mensaje para cuando no hay datos
    const mostrarMensajeSinDatos = (elementId, mensaje = "No hay datos suficientes para mostrar este gráfico") => {
        const canvas = document.getElementById(elementId);
        const container = canvas.parentElement;
        
        // Ocultar el canvas
        canvas.style.display = 'none';
        
        // Crear y mostrar mensaje
        const mensajeElement = document.createElement('p');
        mensajeElement.className = 'sin-datos-mensaje';
        mensajeElement.textContent = mensaje;
        mensajeElement.style.textAlign = 'center';
        mensajeElement.style.padding = '20px';
        mensajeElement.style.fontStyle = 'italic';
        mensajeElement.style.color = '#666';
        
        container.appendChild(mensajeElement);
    };
    
    // GRÁFICO 1: Gráfico de línea
    if (hayDatosLinea) {
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
    } else {
        mostrarMensajeSinDatos('lineChart', "No hay datos de gastos mensuales para mostrar");
    }
    
    // GRÁFICO 2: Gráfico comparativo
    if (hayDatosComparacion) {
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
    } else {
        mostrarMensajeSinDatos('comparisonChart', "No hay datos comparativos de gastos para mostrar");
    }
    
    // GRÁFICO 3: Gráfico de dispersión
    if (hayDatosDispersion) {
        const scatterChartData = {
            datasets: [{
                label: 'Relación Ingresos-Gastos por mes',
                data: datosDispersion,
                backgroundColor: 'rgba(255, 99, 132, 0.7)',
                pointRadius: 8,
                pointHoverRadius: 10
            }]
        };
        
        const scatterCtx = document.getElementById('scatterChart').getContext('2d');
        new Chart(scatterCtx, {
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
    } else {
        mostrarMensajeSinDatos('scatterChart', "No hay datos de relación ingresos-gastos para mostrar");
    }
    
    // GRÁFICO 4: Gráfico de barras apiladas
    if (hayDatosBarras) {
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
    } else {
        mostrarMensajeSinDatos('stackedChart', "No hay datos de desglose de gastos por categoría para mostrar");
    }
});